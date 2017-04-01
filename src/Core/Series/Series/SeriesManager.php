<?php

namespace Core\Series\Series;

use Railken\Laravel\Manager\ModelContract;

use Core\Series\Series\Series;
use Core\Resource\ResourceManager;

class SeriesManager extends ResourceManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new SeriesRepository();
    }

	/**
	 * Fill the entity
	 *
	 * @param ModelContract $entity
	 * @param array $params
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $entity, array $params)
	{


		return parent::fill($entity, $params);

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

		return parent::save($entity);
	}

	/**
	 * To array
	 *
	 * @param Core\Manager\ModelContract $entity
	 *
	 * @return array
	 */
	public function toArray(ModelContract $entity)
	{
		
		return parent::toArray()($entity);
	}
}
