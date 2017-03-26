<?php

namespace Core\Series\Episode;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\ModelManager;

use Core\Series\Episode\Episode;

class EpisodeManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new EpisodeRepository();
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

		$params = $this->getOnlyParams($params, ['name']);

		$entity->fill($params);

		return $entity;

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
			'name' => $entity->name,
		]);

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
		return [];
	}
}
