@extends('layouts.app')

@section('content')
        <div class="container">
            <h1 class="display-3 text-center">{{ __('Offers') }}</h1>
            @if($offers->count() === 0)
                <div class="alert alert-info" role="alert">
                  {{ __('Seems there are no offers, yet.') }}
                </div>
            @endif
            <div class="card-columns">
                @foreach($offers as $offer) 
                    <div class="card mr-1 text-center">
                      <img class="card-img-top img-thumbnail" width="368" height="232" src="{{ $offer->poster_url }}">
                      <div class="card-body">
                        <h5 class="card-title">
                          <small class="lead text-danger">{{ __('Members save :percentage %!', ['percentage' => $offer->discount]) }}</small>
                          <br />
                          {{ $offer->tagline }}
                        </h5>
                        <a href="{{ url('offers', $offer) }}" class="btn btn-primary">{{ __('Details') }}</a>
                      </div>
                    </div>
                @endforeach 
            </div>
             {{-- @include('components.popular-offers') --}}

             {{ $offers->links() }}
        </div>
@endsection