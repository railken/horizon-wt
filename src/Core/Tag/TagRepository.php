<?php

namespace Core\Tag;

use Railken\Laravel\Manager\ModelRepository;

class TagRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
    public $entity = Tag::class;

}
