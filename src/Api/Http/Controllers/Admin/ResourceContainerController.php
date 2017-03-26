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
			'resource_type' => $entity->resource_type,
			'resource_id' => $entity->resource_id,
			'database_name' => $entity->database_name,
			'database_id' => $entity->database_id,
			'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $entity->updated_at->format('Y-m-d H:i:s')
		];
	}

}
