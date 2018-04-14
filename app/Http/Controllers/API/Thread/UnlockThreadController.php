<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Thread;

class UnlockThreadController extends Controller
{
    public function store(Thread $thread)
    {
    	$this->authorize('unlock', $thread);

    	$thread->unlock();

    	return response(['locked' => false]);
    }
}