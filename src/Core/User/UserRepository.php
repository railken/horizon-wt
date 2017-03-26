<?php

namespace Core\User;

use Railken\Laravel\Manager\ModelRepository;
use Core\User\User;

class UserRepository extends ModelRepository
{

	/**
	 * Namespace user
	 *
	 * @var string
	 */
	public $entity = User::class;
	
	/**
	 * Find an user given email
	 *
	 * @param string $email
	 *
	 * @return User
	 */
	public function findByEmail($email)
	{
		return $this->getQuery()->whereEmail($email)->first();
	}

	/**
	 * Find an user given username
	 *
	 * @param string $username
	 *
	 * @return User
	 */
	public function findByUsername($username)
	{
		return $this->getQuery()->whereUsername($username)->first();
	}
}
