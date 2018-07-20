<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @param Request $request 
     * @return Response
     */
    public function show(Request $request)
    {
        return view('home');
    }
}
