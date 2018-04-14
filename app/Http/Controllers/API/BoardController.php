<?php

namespace App\Http\Controllers\API;

use App\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function index(Request $request)
    {
        $this->authorize('index', new Board);

        $boards = ($request->has('search')) 
            ? Board::search($request->input('search'))
            : Board::latest();

        return $boards->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Board);

        $this->validate($request, [
            'name' => 'required|string|min:5|max:50|spamfree',
            'description' => 'string|max:255|spamfree'
        ]);

        return Board::create($request->only(['name', 'description']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Board $board)
    {
        $this->authorize('update', $board);

        $this->validate($request, [
            'name' => 'string|min:5|max:50|spamfree',
            'description' => 'string|max:255|spamfree',
        ]);

        return tap($board)->update($request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Board  $board
     * @return \Illuminate\Http\Response
     */
    public function destroy(Board $board)
    {
        $this->authorize('delete', $board);

        $board->delete();

        return response()->json(['deleted' => true]);
    }
}
