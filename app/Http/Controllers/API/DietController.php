<?php

namespace App\Http\Controllers\API;

use App\Diet;
use App\Http\Controllers\Controller;
use App\Http\Resources\DietResource;
use Illuminate\Http\Request;

class DietController extends Controller
{
    public function index()
    {
        $this->authorize('index', new Diet);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string:in:goal,style,title',
            'sortDirection' => 'nullable|string|in:asc,desc'
        ]);

        $diets = Diet::query()
            ->withCount('meals', 'media')
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                ['*'],
                request('pageName'),
                request('page')
            );

        return DietResource::collection($diets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Diet);

        $validated = $this->validate(request(), [
            'goal' => 'required|string|min:2', 
            'style' => 'required|string|min:2',
            'title' => 'required|string|min:5|unique:diets,title',
            'slug' => 'nullable|string|unique:diets,slug',
            'banner_url' => 'required|string|url',
            'summary' => 'nullable|string',
            'body' => 'nullable|string',
            'view' => 'nullable|string'
        ]);

        $validated['view'] = $validated['view'] ?? 'diets.generic';

        return new DietResource(Diet::create($validated));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Diet  $diet
     * @return \Illuminate\Http\Response
     */
    public function show(Diet $diet)
    {
        $this->authorize('view', $diet);

        return new DietResource($diet->load('meals', 'media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Diet  $diet
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Diet $diet)
    {
        $this->authorize('update', $diet);

        $validated = $this->validate(request(), [
            'goal' => 'string|min:2', 
            'style' => 'string|min:2',
            'title' => 'string|min:5',
            'banner_url' => 'string|url',
            'slug' => 'nullable|string',
            'summary' => 'nullable|string',
            'body' => 'nullable|string',
            'view' => 'nullable|string'
        ]);

        $diet->update($validated);  

        return DietResource::make($diet); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Diet  $diet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Diet $diet)
    {
        $this->authorize('delete', $diet);

        $diet->delete();

        return response()->json(['deleted' => true]);
    }
}
