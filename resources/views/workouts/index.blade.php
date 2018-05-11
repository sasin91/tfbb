@extends('layouts.app')

@section('content')
		<div class="container">
			<h1 class="display-3 text-center">Workouts</h1>
			
			<div class="row">
				@foreach($workouts as $workout) 
					<div class="card mr-1">
					  <img class="card-img-top img-fluid" src="{{ $workout->banner_url }}">
					  <div class="card-body">
					    <h5 class="card-title">{{ $workout->title }}</h5>
					    <a href="{{ url('workouts', $workout) }}" class="btn btn-primary">Go to workout</a>
					  </div>
					</div>
				@endforeach	
			</div>
			@include('components.popular-workouts')
		</div>
@endsection