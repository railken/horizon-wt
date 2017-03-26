<?php

namespace Core\Series\Episode;

use Railken\Laravel\Manager\ModelRepository;

class EpisodeRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
    public $entity = Episode::class;

}
