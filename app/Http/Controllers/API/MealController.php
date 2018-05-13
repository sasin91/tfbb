<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\MealResource;
use App\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Meal);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc'
        ]);

        $meals = Meal::query()
            ->withCount('foods')
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                ['*'],
                request('pageName'),
                request('page')
            );

        return MealResource::collection($meals);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Meal);

        $validated = $this->validate(request(), [
            'name' => 'required|string|min:3',
            'slug' => 'nullable|string',
            'type' => 'required|string',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|string|url',
        ]);

        return MealResource::make(Meal::create($validated));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function show(Meal $meal)
    {
        $this->authorize('view', $meal);

        return MealResource::make($meal->load('foods', 'diets'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Meal $meal)
    {
        $this->authorize('update', $meal);

        $validated = $this->validate(request(), [
            'type' => 'string',
            'name' => 'string|min:3',
            'slug' => 'nullable|string',
            'description' => 'nullable|string',
            'photo_url' => 'nullable|string|url',
        ]);

        $meal->update($validated);

        return MealResource::make($meal);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Meal  $meal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Meal $meal)
    {
        $this->authorize('delete', $meal);

        $meal->delete();

        return response()->json(['deleted' => true]);
    }
}
