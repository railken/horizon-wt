<?php

namespace App\Http\Middleware;

use Core\User\UserManager;
use Closure;
use Auth;

class Logged
{
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @param  string|null  $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null)
	{

		$client = new \GuzzleHttp\Client();

        try {
	        $response = $client->request('GET', env('CORE_URL')."/user/profile", [
	            'headers' => [
					'Authorization' => $request->headers->get('Authorization')
				],
	            'http_errors' => false
	        ]);

			$response = json_decode($response->getBody());

			if ($response->status == 'success') {

				// Authenticate the user
				$um = new UserManager();
				$user = $um->updateFromCore((array)$response->data);
				Auth::login($user);

				return $next($request);
			} else {
				throw new \Exception();
			}
    	} catch (\Exception $e) {
    		abort(401);
    	}

	}
}
