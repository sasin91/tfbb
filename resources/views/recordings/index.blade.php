@extends('layouts.app')

@section('content')
	<div class="container">
		<h1 class="display-3 text-center">Recordings</h1>
		
		<div class="card-columns">
			@foreach($recordings as $recording) 
				<div class="card mr-1">
				  <img class="card-img-top img-fluid" src="{{ optional($recording->getFirstMedia('videos'))->getUrl('thumbnail') }}">
				  <div class="card-body">
				    <h5 class="card-title">{{ $recording->title }}</h5>
				    <a href="{{ url('recordings', $recording) }}" class="btn btn-primary">Go to recording</a>
				  </div>
				</div>
			@endforeach	
		</div>
	</div>
@endsection