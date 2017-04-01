<?php

namespace Core\Sync\Series;

abstract class Series
{

	/**
     * Array
     *
	 * @var array
	 */
	public $attributes = [];

	/**
	 * Convert XML Object to new instance
	 *
	 * @param XML $xml
	 *
	 * @return this
	 */
	abstract public static function xml($xml);

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

	/**
	 * Is valid entity?
	 *
	 * @return boolean
	 */
	public function isValid()
	{
		if (!$this->name || !$this->id)
			return false;

		return true;
	}
}