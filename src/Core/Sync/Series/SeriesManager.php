<?php

namespace Core\Sync\Series;

use Core\Sync\Series\TheTVDB\TheTVDBManager;

class SeriesManager
{

	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->thetvdb = new TheTVDBManager();
	}

	/**
	 * Sync with api/db
	 *
	 * @param string $database_name
	 */
	public function sync()
	{
		$this->thetvdb->sync();
	}
}