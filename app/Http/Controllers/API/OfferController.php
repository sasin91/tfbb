<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\OfferResource;
use App\Offer;
use App\Rules\ViewExists;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('index', new Offer);

        $this->validate(request(), [
            'perPage' => 'nullable|integer|max:15',
            'pageName' => 'string',
            'page' => 'integer',
            'sortBy' => 'nullable|string',
            'sortDirection' => 'nullable|string|in:asc,desc'
        ]);

        $offers = Offer::query()
            ->orderBy(request('sortBy') ?? 'created_at', request('sortDirection') ?? 'desc')
            ->paginate(
                request('perPage'), 
                ['*'],
                request('pageName'),
                request('page')
            );

        return OfferResource::collection($offers);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', new Offer);

        $offer = Offer::create($this->validate($request, [
            'name' => 'required|string|min:5|max:60|unique:offers,name',
            'tagline' => 'string|min:5|max:255',
            'discount' => 'numeric',
            'body' => 'string|min:10|max:65535',
            'poster_url' => 'string|url', // 'active_url'
            'banner_url' => 'string|url',
            'link' => 'string|url',
            'view' => ['string', new ViewExists]
        ]));

        OfferResource::withoutWrapping();

        return new OfferResource($offer);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        $this->authorize('view', $offer);

        OfferResource::withoutWrapping();

        return new OfferResource($offer);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        $this->authorize('update', $offer);

        $offer->update($this->validate($request, [
            'name' => "string|min:5|max:60|unique:offers,name,{$offer->id}",
            'tagline' => 'string|min:5|max:255',
            'discount' => 'numeric',
            'body' => 'string|min:10|max:65535',
            'poster_url' => 'string|url', // 'active_url'
            'banner_url' => 'string|url',
            'link' => 'string|url',
            'view' => ['string', new ViewExists]
        ]));

        OfferResource::withoutWrapping();

        return new OfferResource($offer);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        $this->authorize('delete', $offer);

        $offer->delete();

        return response(['deleted' => true]);
    }
}
