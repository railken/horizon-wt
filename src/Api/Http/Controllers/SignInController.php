<?php

namespace Api\Http\Controllers;

use Illuminate\Http\Request;
use Core\User\UserManager;
use Api\Api\Manager as ApiManager;

class SignInController extends Controller
{

	/**
	 * Sign in a ser
	 *
	 * @param Request $request
	 *
	 * @return response
	 */
	public function index(Request $request)
	{

        $client = new \GuzzleHttp\Client();

        try {
	        $response = $client->request('POST', env('CORE_URL')."/sign-in", [
	            'form_params' => [
	                'username' => $request->input('username'),
	                'password' => $request->input('password'),
	            ],
	            'http_errors' => false
	        ]);
    	} catch (\Exception $e) {
    		return $this->error([]);
    	}

        $body = json_decode($response->getBody());

        if ($body->status == 'success')
            return $this->success(['data' => $body->data]);

        return $this->error(['message' => $body->message]);

	}

}
