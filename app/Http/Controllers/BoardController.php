<?php

namespace App\Http\Controllers;

use App\Board;
use Facades\App\Scores\Popularity;
use Illuminate\Http\Request;

class BoardController extends Controller
{   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->validate(request(), ['search' => 'string|max:50|min:3|spamfree']);

        $boards = Board::published()
                ->latest()
                ->withCount('threads')
                ->orderByDesc('threads_count')
                ->paginate(5);
        
        return view('boards.index')->with('boards', $boards);
    }

    /**
     * Display the specified resource.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $board = Board::withCount('threads')->where('slug', $slug)->firstOrFail();

        $this->authorize('view', $board);

        Popularity::increment($board);

        return view('boards.show')->with('board', $board);
    }
}
