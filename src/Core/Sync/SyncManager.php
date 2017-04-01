<?php

namespace Core\Sync;


class SyncManager
{

	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->series = new \Core\Sync\Series\SeriesManager();
	}

	/**
	 * Sync with api/db
	 *
	 * @param string $database_name
	 */
	public function sync()
	{
		$this->series->sync();
	}
}