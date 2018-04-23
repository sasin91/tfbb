@extends('layouts.app')

@section('content')
		<div class="container">
			<h1 class="display-3 text-center">Diets</h1>
			
			<div class="row">
				@foreach($diets as $diet) 
					<div class="card mr-1">
					  <img class="card-img-top img-fluid" src="{{ $diet->banner_url }}">
					  <div class="card-body">
					    <h5 class="card-title">{{ $diet->title }}</h5>
					    <a href="{{ url('diets', $diet) }}" class="btn btn-primary">Go to diet</a>
					  </div>
					</div>
				@endforeach	
			</div>
			@include('components.popular-diets')
		</div>
@endsection