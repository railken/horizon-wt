<?php

namespace Api\Api;

use DB;

class Manager
{

	/**
	 * Construct
	 */
	public function __construct()
	{

	}

	/**
	 * Retrieve first token client
	 *
	 * @return stdClass
	 */
	public function getFirstTokenClient()
	{
		return DB::table('oauth_clients')->where('personal_access_client',0)->where('password_client',1)->where('revoked',0)->first();
	}
}