<?php

namespace Core\User;

use Core\User\Exceptions\MissingParamException;
use Core\User\Exceptions\UsernameAlreadyUsedException;
use Core\User\Exceptions\EmailAlreadyUsedException;
use Core\User\Exceptions\EmailInvalidException;
use Core\User\Exceptions\WeakPasswordException;
use Core\User\Exceptions\MismatchRepeatPasswordException;

use Core\Manager\Exceptions\InvalidValueParamException;

use Core\User\UserRepository;

use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\ModelContract;


class UserManager extends ModelManager
{

    /**
     * Construct
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

	/**
	 * Fill the entity
	 *
	 * @param ModelContract $entity
	 * @param array $params
	 *
	 * @return ModelContract
	 */
	public function fill(ModelContract $user, array $params)
	{

		$params = array_intersect_key($params, array_flip(['username', 'email', 'role']));

		$user->fill($params);

		return $user;

	}

	/**
	 * This will prevent from saving entity with null value
	 *
	 * @param ModelContract $entity
	 *
	 * @return ModelContract
	 */
	public function save(ModelContract $entity)
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
	 * @param Core\Manager\ModelContract $entity
	 *
	 * @return array
	 */
	public function toArray(ModelContract $entity)
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
