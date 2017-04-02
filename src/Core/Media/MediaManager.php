<?php

namespace Core\Media;

use Railken\Laravel\Manager\ModelContract;
use Railken\Laravel\Manager\ModelManager;
use Illuminate\Support\Facades\Storage;

use Core\Media\Media;

class MediaManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new MediaRepository();
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

		$params = $this->getOnlyParams($params, ['type', 'label', 'path', 'source', 'length']);

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
		return parent::save($entity);
	}

	/**
	 * Upload
	 *
	 * @param ModelContract $entity
	 * @param path $file
	 * @param string $extension
	 *
	 * @return boolean
	 */
	public function upload(ModelContract $entity, $source, $extension)
	{
		$path = $this->getRepository()->generateUniquePath($extension);
		rename($source, public_path()."/media/".$path);

		$this->update($entity, ['path' => $path]);

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
