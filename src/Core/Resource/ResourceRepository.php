<?php

namespace Core\Resource;

use Railken\Laravel\Manager\ModelRepository;

class ResourceRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
    public $entity = Resource::class;

}
