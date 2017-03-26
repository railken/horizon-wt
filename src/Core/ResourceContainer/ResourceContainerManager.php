<?php

namespace Core\ResourceContainer;

use Core\Manager\Manager;
use Core\Manager\ManagerEntityContract;

use Core\ResourceContainer\ResourceContainerRepository;

class ResourceContainerManager extends Manager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new ResourceContainerRepository();
    }

	/**
	 * Fill the entity
	 *
	 * @param ManagerEntityContract $entity
	 * @param array $params
	 *
	 * @return ManagerEntityContract
	 */
	public function fill(ManagerEntityContract $resource_container, array $params)
	{

		$params = $this->getOnlyParams($params, ['resource_type', 'resource_id', 'database_name', 'database_id']);

		// Based on resource_type and resource_id associate with correct model
		// $this->throwExceptionMissingParam(['ResourceContainername', 'password', 'password_repeat', 'email'], $params);

		$resource_container->fill($params);

		return $resource_container;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ManagerEntityContract $entity
	 *
	 * @return ManagerEntityContract
	 */
	public function save(ManagerEntityContract $entity)
	{
		$this->throwExceptionParamsNull([
			'resource_id' => $entity->resource_id,
			'resource_type' => $entity->resource_type,
			'database_name' => $entity->database_name,
			'database_id'=> $entity->database_id,
		]);

		return parent::save($entity);
	}

	/**
	 * To array
	 *
	 * @param Core\Manager\ManagerEntityContract $entity
	 *
	 * @return array
	 */
	public function toArray(ManagerEntityContract $entity)
	{
		return [];
	}
}
