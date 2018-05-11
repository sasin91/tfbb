<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\WorkoutResource;
use App\Workout;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Workout);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc'
        ]);

        $workouts = Workout::query()
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                ['*'],
                request('pageName'),
                request('page')
            );

        return WorkoutResource::collection($workouts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Workout);

        $validated = $this->validate($request, [
            'title' => 'required|string|min:5|max:60|unique:workouts,title',
            'level' => ['required', 'string', Rule::in(config('training.levels'))], 
            'type' => ['required', 'string', Rule::in(config('training.styles'))],
            'summary' => 'nullable|string|max:100',
            'body' => 'nullable|string|max:65535'
        ]);

        return new WorkoutResource(
            Workout::create($validated)
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function show(Workout $workout)
    {
        $this->authorize('view', $workout);

        return WorkoutResource::make($workout->load('media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Workout $workout)
    {
        $this->authorize('update', $workout);

        $validated = $this->validate($request, [
            'title' => "string|min:5|max:60|unique:workouts,title,{$workout->id}",
            'level' => ['string', Rule::in(config('training.levels'))], 
            'type' => ['string', Rule::in(config('training.styles'))],
            'summary' => 'nullable|string|max:100',
            'body' => 'nullable|string|max:65535' // Mysql text limit, 64kb.
        ]);

        $workout->update($validated);

        return new WorkoutResource($workout);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Workout  $workout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Workout $workout)
    {
        $this->authorize('delete', $workout);

        $workout->delete();

        return response(200, ['deleted' => true]);
    }
}
