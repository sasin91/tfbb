@extends('layouts.app')

@section('content')
	<div class="container text-center">

		<h1 class="display-3">{{ $offer->name }}</h1>
		<h3 class="text-info">{{ $offer->tagline }}</h3>

		<hr class="m-y-md">

		<img class="img-fluid" src="{{ $offer->poster_url }}"></img>
		<p>{{ $offer->summary }}</p>

		<p>
			{{ App\Markdown::parse($offer->body) }}
		</p>

		<img class="mx-auto col-12" height="232" width="640" src="{{ $offer->banner_url }}"></img>

		<hr class="divider"></hr>

		<button type="button" class="btn btn-lg btn-success">
			<a class="btn btn-link text-white" target="_blank" href="{{ $offer->link }}">
				<i class="fa fa-angle-double-right"></i>
				{{ __('Get your copy now at a :percentage % discount!', ['percentage' => $offer->discount]) }}
				<i class="fa fa-angle-double-left"></i>
			</a>
		</button>

		<hr class="divider"></hr>

		<div class="d-flex justify-content-center p-1">
			@foreach($offer->testimonials as $testimonial) 
				@include('components.testimonial')
			@endforeach
		</div>
	</div>
@endsection