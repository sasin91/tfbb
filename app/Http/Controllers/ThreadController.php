<?php

namespace App\Http\Controllers;

use App\Board;
use App\Thread;
use Facades\App\Scores\Popularity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ThreadController extends Controller
{
    public function show(Thread $thread)
    {
        $this->authorize('view', $thread);

        Popularity::increment($thread);

        return view('threads.show')->with('thread', $thread->load('creator'));
    }
}
