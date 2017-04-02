<?php

namespace Core\Sync\Series\TheTVDB;

use Core\Sync\Series\Media as BaseMedia;
use DateTime;

class Media extends BaseMedia
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

		$media = new static;

		$media->id = $data->id;
		$media->type = $data->keyType;
		$media->path = $data->fileName;

		return $media;
	}


}