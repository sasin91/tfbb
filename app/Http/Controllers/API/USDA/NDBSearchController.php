<?php

namespace App\Http\Controllers\API\USDA;

use App\Http\Controllers\Controller;
use App\Services\RemoteAPI;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NDBSearchController extends Controller
{
	public function show(Request $request)
	{
		$this->validate($request, [
			'query' => 'required|string|max:20',
			'perPage' => 'nullable|integer|min:5|max:50',
			'page' => 'nullable|integer|min:0'
		]);

		// Cache the NDB search results for a whole week.
		return Cache::remember('NDB::Search['.$request->input('query').']', 10080, function () use ($request) {
			return RemoteAPI::driver('ndb')->search(
				$request->input('query'),
				$request->input('page') ?? 0,
				$request->input('perPage') ?? 25
			); 
		});
	}
}
