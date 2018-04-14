<?php

namespace App\Http\Controllers\API\Thread;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Thread;

class LockThreadController extends Controller
{
    public function store(Thread $thread)
    {
    	$this->authorize('lock', $thread);

    	$thread->lock();

    	return response(['locked' => true]);
    }
}