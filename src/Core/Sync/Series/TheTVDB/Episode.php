<?php

namespace Core\Sync\Series\TheTVDB;

use Core\Sync\Series\Episode as BaseEpisode;
use DateTime;

class Episode extends BaseEpisode
{


	/**
	 * Resolve info
	 *
	 * @param mixed $data
	 *
	 * @return this
	 */
	public static function info($data)
	{

		$episode = new static;

		$episode->name = $data->episodeName;
		$episode->overview = $data->overview;
		$episode->number = $data->airedEpisodeNumber;
		$episode->season_number = $data->airedSeason;

		try {
			$episode->aired_at = (new DateTime($data->firstAired." 00:00:00"));
		} catch (\Exception $e) {

		}

		return $episode;
	}


}