<?php

namespace Api\Http\Controllers\User;

use Api\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Core\User\UserManager;
use Core\Library\LibraryManager;
use Api\Helper\Paginator;
use Railken\Laravel\Manager\ModelContract;
use Core\Library\LibrarySerializer;

class LibraryController extends Controller
{

	/**
	 * Construct
	 *
	 * @param LibraryManager $manager
	 * @param LibrarySerializer $serializer
	 *
	 */
	public function __construct(LibraryManager $manager, LibrarySerializer $serializer)
	{
		$this->manager = $manager;
		$this->serializer = $serializer;
	}

	/**
	 * Serialize data
	 *
	 * @param ModelContract $entity
	 *
	 * @return array
	 */
	public function serialize(ModelContract $entity)
	{
		return $this->serializer->serialize($entity);
	}

	/**
	 * Display a list of all resources
	 *
	 * @param Request $request
	 *
	 * @return response
	 */
	public function index(Request $request)
	{

		$query = $this->manager->getRepository()->queryByUser($this->getUser());

		$searches = $request->input('search', []);

		$query->where(function($qb) use($searches) {
			foreach ($searches as $name => $search) {
				$qb->orWhere($name, $search);
			}
		});

		$paginator = Paginator::retrieve($query, $request->input('page', 1), $request->input('show', 10));

		$sort = [
			'field' => strtolower($request->input('sort_field', 'id')),
			'direction' => strtolower($request->input('sort_direction', 'desc')),
		];

		$results = $query
			->orderBy($sort['field'], $sort['direction'])
			->skip($paginator->getFirstResult())
			->take($paginator->getMaxResults())
			->get();



		foreach ($results as $n => $k) {
			$results[$n] = $this->serialize($k);
		}

		return $this->success([
			'message' => 'ok',
			'data' => [
				'resources' => $results,
				'pagination' => $paginator,
				'sort' => $sort,
				'search' => $searches,
			]
		]);
	}

	/**
	 * Return a json response to insert
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{

		$entity = $this->manager->getRepository()->findResource($id);

		if (empty($entity))
			abort(404);

		$params = $request->all();
		$params = array_merge($params, ['resource_container' => $entity, 'user' => $this->getUser()]);

		$entity = $this->manager->updateOrCreate([
			'resource_container_id' => $entity->id,
			'user_id' => $this->getUser()->id
		], $params);
		
		return $this->success([
			'message' => 'ok',
			'data' => [
				'resource' => $this->serialize($entity)
			]
		]);


	}

	/**
	* Return a json response to insert
	*
	* @Route("/{id}")
	* @Method("DELETE")
	*
	* @param Request $request
	*
	* @return Response
	*/
	public function delete($id, Request $request)
	{

		$entity = $this->manager->getRepository()->findResource($id);

		if (empty($entity))
			abort(404);

		$entity = $this->manager->getRepository()->getQuery()->where([
			'resource_container_id' => $entity->id,
			'user_id' => $this->getUser()->id
		])->first();
		
		if (empty($entity))
			abort(404);
		
		$this->manager->delete($entity);

		return $this->success([
			'message' => 'ok',
			'data' => [
				'resource' => $this->serialize($entity)
			]
		]);


	}

}
