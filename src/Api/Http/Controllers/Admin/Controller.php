<?php

namespace Api\Http\Controllers\Admin;

use Api\Http\Controllers\Controller as BaseController;
use Api\Helper\Paginator;


use Railken\Laravel\Manager\ModelContract;
use Illuminate\Http\Request;


abstract class Controller extends BaseController
{

	/**
	 * Construct
	 *
	 * @param UserManager $manager
	 */
	//abstract public function __construct(Manager $manager);

	/**
	 * Return a new instance of Manager
	 *
	 * @return UserManager
	 */
	public function getManager()
	{
		return $this->manager;
	}

	/**
	 * Return an array rappresentation of entity
	 *
	 * @param ModelContract $entity
	 *
	 * @return array
	 */
	public function serialize(ModelContract $entity)
	{
		return [
			'id' => $entity->id,
		];
	}

	/**
	 * Return a json response of view list
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

		$manager = $this->getManager();

		$query = $manager->getRepository()->getQuery();

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
	public function create(Request $request)
	{

		$manager = $this->getManager();

		$entity = $manager->create($request->all());
		
		return $this->show($entity->id, $request);

	}


	/**
	 * Return a json response to get
	 *
	 * @param Request $request
	 *
	 * @return Response
	*/
	public function show($id, Request $request)
	{

		$manager = $this->getManager();

		$entity = $manager->find($id);

		if (empty($entity))
			abort(404);

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
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$manager = $this->getManager();

		$entity = $manager->find($id);

		if (empty($entity))
			abort(404);

		$manager->update($entity, $request->all());

		return $this->show($entity->id, $request);

	}

	/**
	* Return a json response to insert
	*
	* @Route("/{ids}")
	* @Method("DELETE")
	*
	* @param Request $request
	*
	* @return Response
	*/
	public function delete($ids, Request $request)
	{

		$manager = $this->getManager();

		$entities = $manager->findWhereIn(['id' => explode(";",$ids)]);

		if ($entities->count() == 0)
			abort(404);

		$manager->deleteMultiple($entities);

		return $this->success([
			'message' => 'ok'
		]);

	}

}
