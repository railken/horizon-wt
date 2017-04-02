<?php

namespace Core\Media;

use Railken\Laravel\Manager\ModelRepository;

class MediaRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
    public $entity = Media::class;

    /**
     * Generate unique path
     *
     * @param string $extension
     *
     * @return string
     */
    public function generateUniquePath($extension)
    {
    	do {

    		$path = $this->generateRandomPath().".".$extension;
    		$media = $this->getQuery()->where('path', $path)->count() > 0;

    	} while($media);

    	return $path;
    }

    /**
     * Generate random path
     *
     * @return string
     */
    public function generateRandomPath()
    {
    	return time()."_".md5("a".microtime());
    }
}
