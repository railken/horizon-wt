<?php

namespace Core\User;

use Core\User\Exceptions\MissingParamException;
use Core\User\Exceptions\UsernameAlreadyUsedException;
use Core\User\Exceptions\EmailAlreadyUsedException;
use Core\User\Exceptions\EmailInvalidException;
use Core\User\Exceptions\WeakPasswordException;
use Core\User\Exceptions\MismatchRepeatPasswordException;

use Core\Manager\Exceptions\InvalidValueParamException;

use Core\User\User;
use Core\User\UserRepository;

use Core\Manager\Manager;
use Core\Manager\ManagerEntityContract;

class UserManager extends Manager
{

	/**
	 * Entity class
	 *
	 * @var string
	 */
	protected $entity = User::class;

    /**
	 * Repository
	 *
     * @var UserRepository
     */
    protected $repository;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * Retrieve repository
     *
     * @return Repository
     */
    public function getRepository()
    {
        return $this->repository;
    }


	/**
	 * Fill the entity
	 *
	 * @param ManagerEntityContract $entity
	 * @param array $params
	 *
	 * @return ManagerEntityContract
	 */
	public function fill(ManagerEntityContract $user, array $params)
	{

		$params = array_intersect_key($params, array_flip(['username', 'email', 'role']));

		$user->fill($params);

		return $user;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ManagerEntityContract $entity
	 *
	 * @return ManagerEntityContract
	 */
	public function save(ManagerEntityContract $entity)
	{
		$this->throwExceptionParamsNull([
			'username' => $entity->username,
			'email' => $entity->email,
		]);

		return parent::save($entity);
	}

	/**
	 * To array
	 *
	 * @param Core\Manager\ManagerEntityContract $entity
	 *
	 * @return array
	 */
	public function toArray(ManagerEntityContract $entity)
	{
		return [];
	}

	/**
	 * Manage entity user from core auth
	 *
	 * @param array $params
	 *
	 * @return User
	 */
	public function updateFromCore(array $params)
	{

		$user = $this->getRepository()->findByEmail($params['email']);

		if (!$user) {
			$user = $this->getRepository()->newEntity();
		}

		$this->fill($user, $params);
		$this->save($user);

		return $user;
	}
}
