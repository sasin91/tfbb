<?php

namespace App\Http\Controllers;

use App\Diet;
use Facades\App\Scores\Popularity;
use Illuminate\Http\Request;

class DietController extends Controller
{
    public function index()
    {
    	$this->authorize('index', new Diet);

    	return view('diets.index')->with('diets', Diet::all(['id', 'title', 'slug']));
    }

    public function show(Diet $diet)
    {
    	$this->authorize('view', $diet);

    	Popularity::increment($diet);

    	return view('diets.show')->with('diet', $diet->load(['foods', 'allergies']));
    }
}
