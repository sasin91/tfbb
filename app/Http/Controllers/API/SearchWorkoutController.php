<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Workout;

class SearchWorkoutController extends Controller
{
    public function __invoke()
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

        return Workout::search(request('search'))
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                request('pageName'),
                request('page')
            );
    }
}