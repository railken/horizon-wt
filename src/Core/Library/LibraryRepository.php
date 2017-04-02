<?php

namespace Core\Library;

use Railken\Laravel\Manager\ModelRepository;

use Core\ResourceContainer\ResourceContainerManager;
use Core\ResourceContainer\ResourceContainerUser;
use Core\User\User;

class LibraryRepository extends ModelRepository
{

	/**
	 * Class name entity
	 *
	 * @var string
	 */
    public $entity = ResourceContainerUser::class;

    /**
	 * Get resource container manager
	 *
	 * @return ResourceContainerManager
	 */
    public function getResourceContainerManager()
    {
    	return new ResourceContainerManager();
    }

    /**
     * Get resources by user
     *
     * @param User $user
     *
     * @return QueryBuilder
     */
    public function queryByUser(User $user)
    {

    	return $this
    	->getQuery()
    	->join('resource_containers', 'resource_containers.id', '=','resource_containers_users.resource_container_id')
    	->join('users', 'users.id', '=', 'resource_containers_users.user_id')
    	->where('users.id', $user->id)
    	->select('resource_containers_users.*');
    }

    /**
     * Find resource
     *
     * @param integer $id
     *
     * @return ResourceContainer
     */
    public function findResource($id)
    {
    	return $this
    	->getResourceContainerManager()
    	->find($id);
    }
}
