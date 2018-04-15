<?php

namespace App\Http\Controllers\API\Kiosk\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Workout;

class WorkoutSearchController extends Controller
{
    public function show()
    {
        $this->authorize('search', new Workout);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc',
            'search' => 'string|min:3|max:40'
        ]);

        $search = request('search');

        return Workout::query()
	        ->where('level', 'like', $search)
	        ->orWhere('type', 'like', $search)
	        ->orWhere(function ($query) use ($search) {
	        	$query
	        		->where('title', 'like', $search)
	        		->orWhere('title', 'contains', $search);
	        })
	        ->orWhere('summary', 'contains', $search)
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                request('pageName'),
                request('page')
            );
    }
}