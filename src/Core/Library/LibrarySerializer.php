<?php

namespace Core\Library;

use Core\ResourceContainer\ResourceContainerSerializer;
use Railken\Laravel\Manager\ModelContract;

class LibrarySerializer
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

		$serializer = new ResourceContainerSerializer();

		return [
			'container' => $serializer->serialize($entity->resource_container),
			'user' => [
				'status' => $entity->status,
				'note' => $entity->note,
				'review' => $entity->review,
				'rating' => $entity->rating
			]
		];
	}
	
}