<?php

namespace Core\Sync\Series;

abstract class SeriesCollection
{
	/**
	 * Convert XML Object to new instance
	 *
	 * @param XML $xml
	 *
	 * @return this
	 */
	abstract public static function xml($xml);

}