<?php

namespace Api\Http\Controllers\Admin;

use Railken\Laravel\Manager\ModelContract;
use Core\Series\Series\SeriesManager;

class SeriesController extends Controller
{

	/**
	 * Construct
	 *
	 * @param UserManager $manager
	 */
	public function __construct(SeriesManager $manager)
	{
		$this->manager = $manager;
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
			'name' => $entity->name,
			'overview' => $entity->overview,
			'status' => $entity->status,
			'type' => $entity->type,
			'container' => [
				'id' => $entity->container->id,
				'resource_type' => $entity->container->resource_type,
				'resource_id' => $entity->container->resource_id,
				'database_name' => $entity->container->database_name,
				'database_id' => $entity->container->database_id,
				'created_at' => $entity->container->created_at->format('Y-m-d H:i:s'),
				'updated_at' => $entity->container->updated_at->format('Y-m-d H:i:s')
			],
			'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $entity->updated_at->format('Y-m-d H:i:s')
		];
	}

}
