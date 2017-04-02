<?php

namespace Core\Sync\Series\TheTVDB;

use Core\ResourceContainer\ResourceContainerManager;
use Core\ResourceContainer\ResourceContainer;

use Core\Series\Series\SeriesManager;
use Core\Series\Episode\EpisodeManager;

use Core\Media\MediaManager;

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
				'Authorization' => 'Bearer '.Cache::get('sync.resources.thetvdb.access_token'),
				'Accepted-Language' => 'en'
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

		$this->authenticate();

		$last_updated_at = Cache::get('sync.resources.thetvdb.updated_at');

		if (!$last_updated_at) {
			$last_updated_at = time()-3600*24;
		}

		Cache::put('sync.resources.thetvdb.updated_at', $last_updated_at);

		// 1. Request updates
		$now = time();
		$response = $this->client_2->get("/updated/query?fromTime={$last_updated_at}&toTime={$now}");
		$body = json_decode($response->getBody());

		return SeriesCollection::info($body->data);

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

		$series = Series::info($series->data);

		# Retrieve episodes
		$next = 1;

		do{	
			$response = $this->client_2->get("/series/{$series_id}/episodes?page={$next}");
			$body = json_decode($response->getBody());
			$series->addEpisodes($body->data);
			$next = $body->links->next;
		} while($next != null);

		# Retrieve actors


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

		$resource_container = $this->syncResourceContainer($series);

		if (!$resource_container)
			return false;

		$this->syncTags($resource_container, $series);
		$this->syncActors($resource_container, $series);
		$this->syncEpisodes($resource_container, $series);

		return true;

	}

	/**
	 * Sync resource container
	 *
	 * @param Series $series
	 *
	 * @return ResourceContainer
	 */
	public function syncResourceContainer(Series $series)
	{
		$manager = $this->container_manager;

		$resource_container = $manager->getRepository()->getQuery()->where(['database_name' => 'thetvdb', 'database_id' => $series->id])->first();

		if ($resource_container && $series->updated_at <= $resource_container->database_updated_at)
			return null;

		if (!$series->isValid())
			return null;

		$params = $series->toArray();
		$params['database_updated_at'] = $series->updated_at;

		# Resource and container
		if (!$resource_container) {
			$params['resource_type'] = 'series';
			$params['database_name'] = 'thetvdb';
			$params['database_id'] = $series->id;
			$resource_container = $manager->create($params);
		} else {
			$manager->update($resource_container, $params);
		}

		return $resource_container;

	}

	/**
	 * Sync tags given series
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

	/**
	 * Sync actors
	 *
	 * @param ResourceContainer $resource_container
	 * @param Series $series
	 *
	 * @return void
	 */
	public function syncActors(ResourceContainer $resource_container, Series $series)
	{

	}

	/**
	 * Sync episodes
	 *
	 * @param ResourceContainer $resource_container
	 * @param Series $series
	 *
	 * @return void
	 */
	public function syncEpisodes(ResourceContainer $resource_container, Series $series)
	{
		$episodes = collect();

		$manager = new EpisodeManager();

		foreach ($series->episodes as $raw_episode) {
			$episode = $manager->findOrCreate([
				'number' => $raw_episode->number,
				'series_id' => $resource_container->resource->id
			]);

			$manager->fill($episode, $raw_episode->toArray());
			$manager->save($episode);


			$episodes[] = $episode->id;
		}

		if ($episodes->count() == 0)
			return;


		$manager
			->getRepository()
			->getQuery()
			->where('series_id', $resource_container->resource->id)
			->whereNotIn('id', $episodes)
			->delete();


	}

	/**
	 * Retrieve images 
	 *
	 * @param integer $series_id
	 *
	 * @return boolean
	 */
	public function getMedia($series_id)
	{

		$this->authenticate();
		$media = [];

		# Retrieve images
		foreach (['poster', 'fanart', 'poster'] as $image) { // poster, series, images
			$response = $this->client_2->get("/series/{$series_id}/images/query?keyType={$image}");
			$body = json_decode($response->getBody());

			foreach ($body->data as $img) {
				$media[] = Media::info($img);
			}
		} 

		return $media;

	}

	/**
	 * Sync media
	 *
	 * @param integer $series_id
	 * @param integer $media_url
	 *
	 * @return boolean
	 */
	public function syncMedia($series_id, $media_type, $media_url)
	{
		
		$rm = $this->container_manager;
		$resource_container = $rm->getRepository()->getQuery()->where(['database_name' => 'thetvdb', 'database_id' => $series_id])->first();

		$mm = new MediaManager();
		$media = $mm->findOrCreate(['type' => $media_type, 'source' => $media_url]);

		$response = $this->client_1->head("/banners/".$media_url);
		$headers = $response->getHeaders();
		$media_length = $headers['Content-Length'][0];
		# Start to save only if content-length is different 

		if ($media->length != $media_length) {
			$extension = pathinfo($media_url, PATHINFO_EXTENSION);
			$tmp = tmpfile();
			$response = $this->client_1->get("/banners/".$media_url, ['sink' => $tmp]);
			$mm->upload($media, stream_get_meta_data($tmp)['uri'], $extension);
			$mm->update($media, ['type' => $media_type,'length' => $media_length]);
			$resource_container->media()->syncWithoutDetaching([$media->id]);
		}

	}
}