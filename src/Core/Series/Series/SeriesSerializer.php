<?php

namespace Core\Series\Series;

use Railken\Laravel\Manager\ModelContract;
class SeriesSerializer
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

		return [
			'id' => $entity->id,
			'type' => $entity->type,
			'name' => $entity->name,
			'overview' => $entity->overview,
			'status' => $entity->status,
			'created_at' => $entity->created_at->format('Y-m-d H:i:s'),
			'updated_at' => $entity->updated_at->format('Y-m-d H:i:s')
		];
	}
	
}