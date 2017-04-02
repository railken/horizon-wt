<?php

namespace Core\Library;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\ModelManager;

use Core\User\UserManager;
use Core\ResourceContainer\ResourceContainerManager;
use Core\Library\Library;

class LibraryManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new LibraryRepository();
        parent::__construct();
    }

    /**
	 * Update or create
	 *
	 * @param array $criteria
	 * @param array $params
	 *
	 * @return ModelContract
	 */
	public function updateOrCreate(array $criteria, array $params)
	{
		$entity = $this->getRepository()->getQuery()->where($criteria)->first();

		return $entity ? $this->update($entity, $params) : $this->create($params);
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

		$params = $this->getOnlyParams($params, ['resource_container', 'resource_container_id', 'user', 'user_id', 'status', 'note', 'review', 'rating']);

		$this->fillManyToOneById($entity, new UserManager(), $params, 'user');
		$this->fillManyToOneById($entity, new ResourceContainerManager(), $params, 'resource_container');

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
		/*$this->throwExceptionParamsNull([
			'name' => $entity->name,
		]);*/

		$entity->resource_container()->associate($this->vars->get('resource_container', $entity->resource_container));
		$entity->user()->associate($this->vars->get('user', $entity->user));

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
