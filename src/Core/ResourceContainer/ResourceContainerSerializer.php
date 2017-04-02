<?php

namespace Core\ResourceContainer;

use Railken\Laravel\Manager\ModelContract;
use Core\Series\Series\SeriesSerializer;
use Core\Tag\TagSerializer;

class ResourceContainerSerializer
{
	
	/**
	 * Return an array rappresentation of entity
	 *
	 * @param ModelContract $entity
	 *
	 * @return array
	 */
	public function serialize(ModelContract $entity)
	{

		if ($entity->type == 'series')
			$serializer = new SeriesSerializer();


		return [
			'id' => $entity->id,
			'type' => $entity->type,
			'resource' => $serializer->serialize($entity->resource),
			'tags' => $this->tags($entity->tags),
			'database_name' => $entity->database_name,
			'database_id' => $entity->database_id,
			'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $entity->updated_at->format('Y-m-d H:i:s')
		];
	}


	/**
	 * Serialize array tags
	 *
	 * @param Collection $tags
	 *
	 * @return Collection
	 */
	public function tags($tags)
	{
		$serializer = new TagSerializer();

		return $tags->map(function($tag) use($serializer) {
			return $serializer->serialize($tag);
		});
	}
	
}