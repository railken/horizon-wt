<?php

namespace Core\ResourceContainer;

use Core\ResourceContainer\ResourceContainer;
use Railken\Laravel\Manager\ModelRepository;

class ResourceContainerRepository extends ModelRepository
{

	/**
	 * Namespace Entity
	 *
	 * @var string
	 */
	public $entity = ResourceContainer::class;

}
