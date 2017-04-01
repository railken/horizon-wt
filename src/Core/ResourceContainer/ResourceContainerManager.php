<?php

namespace Core\ResourceContainer;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ModelContract;

use Core\ResourceContainer\ResourceContainerRepository;
use Core\Series\Series\SeriesManager;
use Core\Manga\Manga\MangaManager;

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
     * Retrieve resource manager given type
     *
     * @param string $type
     *
     * @return ModelManager
     */
    public function getResourceManagerByType($type)
    {
    	if ($type == 'series')
			return new SeriesManager();
		

		if ($type == 'manga')
			return new MangaManager();
		

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

		$params = $this->getOnlyParams($params, ['resource_type', 'resource_id', 'database_name', 'database_id', 'name', 'overview', 'status']);

		if (!$resource_container->resource) {

			$rm = $this->getResourceManagerByType($params['resource_type']);
			
			$resource = $rm->getRepository()->newEntity();
			$rm->fill($resource, $params);
			//$rm->save($resource);
			

			// This sould not create the entity
			//$resource = $rm->create($params);

			
		} else {
			$resource = $resource_container->resource;

			$rm = $this->getResourceManagerByType($resource_container->resource->type);

			$resource = $rm->update($resource, $params);

			$params['resource_type'] = $resource_container->resource->type;

		}

		$resource->save();


		# Remove All other params
		# Note: A resource type cannot be changed
		$params = $this->getOnlyParams($params, ['resource_type', 'database_name', 'database_id']);

		// Based on resource_type and resource_id associate with correct model
		// $this->throwExceptionMissingParam(['ResourceContainername', 'password', 'password_repeat', 'email'], $params);

		$this->vars['resource'] = $resource;
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
			'database_name' => $entity->database_name,
			'database_id'=> $entity->database_id,
		]);

		$this->executeQueue();


		$entity->resource()->associate($this->vars['resource']);

		parent::save($entity);
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

	/**
	 * Remove a ModelContract
	 *
	 * @param Railken\Laravel\Manager\ModelContract $entity
	 *
	 * @return void
	 */
	public function delete(ModelContract $entity)
	{	

		// First: delete the resource associated with it
		$rm = $this->getResourceManagerByType($entity->resource->type);
		$rm->delete($entity->resource);

		// Now we can delete the container
		$entity->delete();
	}
}
