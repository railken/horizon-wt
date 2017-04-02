<?php

namespace Core\Sync\Series\TheTVDB;

use Core\Sync\Series\Series as BaseSeries;
use DateTime;

class Series extends BaseSeries
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

		$series = new static;

		$series->id = $xml->id;
		$series->updated_at = (new DateTime())->setTimestamp($xml->time);

		return $series;
	}

	/**
	 * Resolve info
	 *
	 * @param mixed $data
	 *
	 * @return this
	 */
	public static function info($data)
	{

		$series = new static;

		$series->id = $data->id;
		$series->name = $data->seriesName ?? null;
		$series->updated_at = (new DateTime())->setTimestamp($data->lastUpdated);
		$series->tags = $data->genre ?? null;
		return $series;
	}

	/**
	 * Add episodes
	 *
	 * @param array $episodes
	 *
	 * @return this
	 */
	public function addEpisodes(array $episodes)
	{

		foreach ($episodes as $episode) {
			$this->episodes[] = Episode::info($episode);
		}

		return $this;
	}

}