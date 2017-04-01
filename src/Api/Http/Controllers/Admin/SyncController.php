<?php

namespace Api\Http\Controllers\Admin;

use Illuminate\Http\Request;

class SyncController extends Controller
{

	/**
	 * Given the type of resource download the resource
	 */
	public function index(Request $request)
	{
		
		\Artisan::queue('core:sync:update', [
	        'database_name' => $request->input('database_name'), '--queue' => 'default'
	    ]);
		
	}

}
