<?php

namespace App\Http\Controllers;

use App\Offer;
use Illuminate\Http\Request;

class OfferController extends Controller
{
	public function index()
	{
		return view('offers.index')->with('offers', Offer::latest()->paginate());
	}

	public function show(Offer $offer)
	{
		return view($offer->view)->with('offer', $offer->load('testimonials'));
	}
}
