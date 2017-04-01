<?php

namespace Core\Tag;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\ModelManager;

use Core\Tag\Tag;

class TagManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new TagRepository();
    }

    /**
     * First or create
     *
     * @param array $params
     *
     * @return ModelContract
     */
    public function firstOrCreate(array $params)
    {

    	$tag = $this
    		->getRepository()
    		->getQuery()
    		->where($params)
    		->where('aliases','LIKE','%"'.$params['name'].'"%')
    		->first();

    	if ($tag)
    		return $tag;

    	return $this->create($params);


    }

    /**
     * Create a new entity
     *
     * @param array $params
     *
     * @return ModelContract
     */
    public function create(array $params)
    {
    	
    	if (!isset($params['aliases'])) {
    		$params['aliases'] = [];
    	}

    	if (!in_array($params['name'], $params['aliases']))
    		$params['aliases'][] = $params['name'];

    	return parent::create($params);
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

		$params = $this->getOnlyParams($params, ['name', 'aliases', 'description']);

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
