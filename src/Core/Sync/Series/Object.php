<?php

namespace Core\Sync\Series;

abstract class Object
{
	/**
     * Array
     *
	 * @var array
	 */
	public $attributes = [];

	/**
	 * Set
	 *
	 * @param string $attribute
	 * @param mixed $value
	 *
	 * @return void
	 */
	public function __set($attribute, $value)
	{
		if (!isset($this->{$attribute})) {
			$this->attributes[$attribute] = $value;
		}
	}

	/**
	 * Get
	 *
	 * @param string $attribute
	 *
	 * @return mixed
	 */
	public function __get($attribute)
	{
		if (!isset($this->{$attribute}) && isset($this->attributes[$attribute])) {
			return $this->attributes[$attribute];
		}

		return null;
	}

	/**
	 * Convert to array
	 *
	 * @return array
	 */
	public function toArray()
	{
		return $this->attributes;
	}
}