<?php

namespace Core\Sync\Series\TheTVDB;

use Core\Sync\Series\SeriesCollection as BaseSeriesCollection;

class SeriesCollection extends BaseSeriesCollection
{

	/**
	 * Convert XML Object to new instance
	 *
	 * @param XML $xml
	 *
	 * @return this
	 */
	public static function xml($xml)
	{

		$r = collect();

		foreach ($xml->Series as $serie) {
			$r[] = Series::xml($serie);
		}

		return $r;

	}

}