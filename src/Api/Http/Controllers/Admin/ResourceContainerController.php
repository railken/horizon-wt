<?php

namespace Api\Http\Controllers\Admin;

use Core\Manager\ManagerEntityContract;
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
	 * @param Core\Manager\ManagerEntityContract $entity
	 *
	 * @return array
	 */
	public function serialize(ManagerEntityContract $entity)
	{
		return [
			'id' => $entity->id,
		];
	}

}
