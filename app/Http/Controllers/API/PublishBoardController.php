<?php

namespace App\Http\Controllers\API;

use App\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PublishBoardController extends Controller
{
    public function __invoke(Board $board)
    {
    	return tap($board)->publish();
    }
}
