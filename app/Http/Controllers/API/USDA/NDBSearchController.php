<?php

namespace App\Http\Controllers\API\USDA;

use App\Http\Controllers\Controller;
use App\Services\RemoteAPI;
use Illuminate\Http\Request;

class NDBSearchController extends Controller
{
	public function show(Request $request)
	{
		$this->validate($request, [
			'query' => 'required|string|max:20',
			'perPage' => 'nullable|integer|min:5|max:50',
			'page' => 'nullable|integer|min:0'
		]);

		return RemoteAPI::driver('ndb')->search(
			$request->input('query'),
			$request->input('page') ?? 0,
			$request->input('perPage') ?? 25
		); 
	}
}
