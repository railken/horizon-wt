<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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

		if (\Auth::user()->role != 'admin') {
			abort(401);
		}

		return $next($request);
	}
}
