<?php

namespace Core\Sync\Series;

abstract class Episode extends Object
{



	/**
	 * Is valid entity?
	 *
	 * @return boolean
	 */
	public function isValid()
	{

		$required = ['number'];

		foreach ($required as $field) {
			if (!$this->$field) {
				return false;
			}
		}


		return true;
	}
}