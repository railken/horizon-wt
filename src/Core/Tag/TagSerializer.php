<?php

namespace Core\Tag;

use Railken\Laravel\Manager\ModelContract;

class TagSerializer
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
			'name' => $entity->name,
			'aliases' => $entity->aliases,
		];
	}
	
}