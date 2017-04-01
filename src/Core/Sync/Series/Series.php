<?php

namespace Core\Sync\Series;

abstract class Series extends Object
{


	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->episodes = collect();
	}

	/**
	 * Convert XML Object to new instance
	 *
	 * @param XML $xml
	 *
	 * @return this
	 */
	abstract public static function xml($xml);

	/**
	 * Is valid entity?
	 *
	 * @return boolean
	 */
	public function isValid()
	{

		$required = ['name', 'id'];

		foreach ($required as $field) {
			if (!$this->$field) {
				return false;
			}
		}


		return true;
	}
}