<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecordingResource;
use App\Recording;
use Illuminate\Http\Request;

class RecordingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Recording);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc'
        ]);

        $results = Recording::query()
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                ['*'],
                request('pageName'),
                request('page')
            );

        return RecordingResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Recording);

        return Recording::create($this->validate($request, [
            'category' => 'required|string|min:5|max:50', 
            'title' => 'required|string|min:3|max:255|unique:recordings,title', 
            'summary' => 'nullable|string|max:100',
            'body' => 'nullable|string|max:65535'
        ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function show(Recording $recording)
    {
        $this->authorize('view', $recording);

        return RecordingResource::make($recording->load('media'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Recording $recording)
    {
        $this->authorize('update', $recording);

        return tap($recording)->update($this->validate($request, [
            'category' => 'string|min:5|max:50', 
            'title' => "string|min:3|max:255|unique:recordings,title,{$recording->id}", 
            'summary' => 'nullable|string|max:100',
            'body' => 'nullable|string|max:65535'
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Recording  $recording
     * @return \Illuminate\Http\Response
     */
    public function destroy(Recording $recording)
    {
        $this->authorize('delete', $recording);

        $recording->delete();

        return response(['deleted' => true]);
    }
}
