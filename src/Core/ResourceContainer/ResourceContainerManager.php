<?php

namespace Core\ResourceContainer;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ModelContract;

use Core\ResourceContainer\ResourceContainerRepository;

class ResourceContainerManager extends ModelManager
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
	 * @param ModelContract $entity
	 * @param array $params
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $resource_container, array $params)
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
	 * @param ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
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
	 * @param ModelContract $entity
	 *
	 * @return array
	 */
	public function toArray(ModelContract $entity)
	{
		return [];
	}
}
