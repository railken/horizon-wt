<?php

namespace Core\Sync\Series\TheTVDB;

use Core\Series\Series\SeriesManager;
use Core\ResourceContainer\ResourceContainerManager;
use Core\ResourceContainer\ResourceContainer;

use Component\Str;
use Illuminate\Support\Facades\Cache;
use Core\Tag\TagManager;

class TheTVDBManager
{

	/**
	 * Construct
	 */
	public function __construct()
	{
		
		$this->url = env('SYNC_THETVDB_URL');

		$this->token = env('THETVDB_TOKEN_API');
		$this->initialize();

		$this->manager = new SeriesManager();
		$this->container_manager = new ResourceContainerManager();
	}

	/**
	 * initialize clients
	 *
	 * @return void
	 */
	public function initialize()
	{
		$this->client_1 = new \GuzzleHttp\Client(['base_uri' => env('THETVDB_API_V1_URL')]);

		$this->client_2 = new \GuzzleHttp\Client([
			'headers' => [
				'Content-Type' => 'application/json',
				'Authorization' => 'Bearer '.Cache::get('sync.resources.thetvdb.access_token')
			], 
			'base_uri' => env('THETVDB_API_V2_URL')
		]);
	}

	/**
	 * Authenticate to api
	 *
	 * @return void
	 */
	public function authenticate()
	{
		
		if (!$token = Cache::get('sync.resources.thetvdb.access_token')) {

			print_r(json_encode([
				'apikey' => $this->token,
				'userkey' => env('THETVDB_TOKEN_USER'),
				'username' => env('THETVDB_USERNAME'),
			]));

			$response = $this->client_2->post("/login", ['json' => [
				'apikey' => $this->token,
				'userkey' => env('THETVDB_TOKEN_USER'),
				'username' => env('THETVDB_USERNAME'),
			]]);


			$response = json_decode($response->getBody());
			$token = $response->token;

			Cache::put('sync.resources.thetvdb.access_token', $token, 3600);
		}

		$this->initialize();
	}

	/**
	 * Sync with api/db
	 *
	 * @return Collection
	 */
	public function toUpdate()
	{

		// Retrieved one series
		$series = $this->manager->getRepository()->getQuery()->first();

		// If no series is retrieved, than it's the first sync
		if (!$series)
			return $this->all();

		return $this->latest();

	}

	/**
	 * Extract zip
	 *
	 * @param string $from
	 * @param string $to
	 *
	 * @return void
	 */
	public function extract($from, $to)
	{

		$zip = new \ZipArchive;

		if($zip->open($from) === TRUE){
		    $zip->extractTo($to);
		    $zip->close();
		}

	}

	/**
	 * Retrieve all series
	 *
	 * @return Collection
	 */
	public function all()
	{
		// 1. Define files
		$dir = tempnam(sys_get_temp_dir(), '');
		unlink($dir);
		mkdir($dir, 0777, true);

		$zip = $dir."/updates_all.zip";
		$xml = $dir."/updates_all.xml";

		// 1. Download ZIP
		$this->client_1->get("/api/{$this->token}/updates/updates_all.zip", ["save_to" => $zip]);

		// 2. Extract
		$this->extract($zip, $dir);
		$xml = Str::xml(file_get_contents($xml));

		// 3. Resolve
		return SeriesCollection::xml($xml);

	}

	/**
	 * Retrieve all series
	 *
	 * @return Collection
	 */
	public function latest()
	{

		$last_updated_at = Cache::get('sync.resources.thetvdb.updated_at');

		if (!$last_updated_at) {
			$last_updated_at = time()-3600*24;
		}

		Cache::set('sync.resources.thetvdb.updated_at', $last_updated_at);
	}

	/** 
	 * Retrieve all information about a series
	 *
	 * @param integer $series_id
	 *
	 * @return boolean
	 */
	public function get($series_id)
	{

		
		$this->authenticate();

		$response = $this->client_2->get("/series/{$series_id}");
		$series = json_decode($response->getBody());


		//$response = $this->client_2->get("/series/{$series_id}/episodes");
		//$episodes = json_decode($response->getBody());

		//print_r($series);
		//print_r($episodes);

		$series = Series::info($series->data);
		//$series->setEpisodes($episodes);

		return $series;
	}

	/** 
	 * Retrieve all information about a series
	 *
	 * @param integer $series_id
	 *
	 * @return boolean
	 */
	public function sync($series_id)
	{

		$series = $this->get($series_id);

		$manager = $this->container_manager;

		$resource_container = $manager->getRepository()->getQuery()->where(['database_name' => 'thetvdb', 'database_id' => $series_id])->first();

		if (!$series->isValid())
			return;

		$params = $series->toArray();

		# Resource and container
		if (!$resource_container) {
			$params['resource_type'] = 'series';
			$params['database_name'] = 'thetvdb';
			$params['database_id'] = $series_id;
			$resource_container = $manager->create($params);
		} else {
			$manager->update($resource_container, $params);
		}

		# Tags
		$this->syncTags($resource_container, $series);


		# Actors

		# Episodes

		# Images
	}

	/**
	 * Public function sync tags given series
	 *
	 * @param ResourceContainer $resource_container
	 * @param Series $series
	 *
	 * @return void
	 */
	public function syncTags(ResourceContainer $resource_container, Series $series)
	{

		$tags = collect();

		$manager = new TagManager();

		foreach ((array)$series->tags as $tag) {
			$tags[] = $manager->firstOrCreate([
				'name' => $tag
			]);
		}

		$resource_container->tags()->sync($tags->map(function($tag) {
			return $tag->id;
		}));
	}
}