<?php

namespace App\Http\Controllers\API\Board;

use App\Board;
use App\Http\Controllers\Controller;
use App\Thread;
use Illuminate\Http\Request;

class BoardThreadController extends Controller
{
    public function index(Board $board)
    {
        $this->authorize('index', new Thread);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer'
        ]);

        return $board->threads()->withCount('replies')->with(['creator', 'creator.profile'])->paginate(
            request('perPage'), 
            ['*'],
            request('pageName'),
            request('page')
        );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Board $board)
    {
        $this->authorize('create', new Thread);

        $this->validate($request, [
            'title' => 'required|string|min:5|max:50|spamfree|unique:threads,title',
            'body' => 'required|string|min:5|max:50|spamfree',
            'summary' => 'nullable|string|max:20|spamfree'
        ]);

        return $board->threads()->create([
            'creator_id' => $request->user()->id,
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'summary' => $request->input('summary')
        ]);
    }
}
