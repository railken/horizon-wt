<?php

namespace Api\Http\Controllers\Admin;

use Railken\Laravel\Manager\ModelContract;
use Core\ResourceContainer\ResourceContainerManager;

class ResourceContainerController extends Controller
{

	/**
	 * Construct
	 *
	 * @param UserManager $manager
	 */
	public function __construct(ResourceContainerManager $manager)
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
			'resource' => [
				'id' => $entity->resource->id,
				'type' => $entity->resource->type,
				'name' => $entity->resource->name,
				'overview' => $entity->resource->overview,
				'status' => $entity->resource->status,
				'created_at' => $entity->resource->created_at->format('Y-m-d H:i:s'),
				'updated_at' => $entity->resource->updated_at->format('Y-m-d H:i:s')
			],
			'database_name' => $entity->database_name,
			'database_id' => $entity->database_id,
			'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $entity->updated_at->format('Y-m-d H:i:s')
		];
	}

}
