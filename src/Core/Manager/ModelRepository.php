<?php

namespace Core\Manager;

abstract class ModelRepository
{

	/**
	 * Class entity
	 *
	 * @var string
	 */
	public $entity;

	/**
	 * Retrieve new instance of entity
	 *
	 * @param array $params
	 *
	 * @return entity
	 */
	public function newEntity(array $params = [])
	{
		return new $this->entity($params);
	}

	/**
	 * Find by primary
	 *
	 * @param integer $id
	 *
	 * @return User
	 */
	public function find($id)
	{
		return $this->getQuery()->where('id',$id)->first();
	}

	/**
	 * Find where in
	 *
	 * @param array
	 *
	 * @return Collection
	 */
	public function findWhereIn($params)
	{
		$q = $this->getQuery();

		foreach ($params as $name => $value) {
			$q->whereIn($name, $value);
		}

		return $q->get();
	}

	/**
	 * Return query
	 *
	 * @return QueryBuilder
	 */
	public function getQuery()
	{
		return $this->newEntity()->newQuery();
	}
}
