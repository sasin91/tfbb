@extends('layouts.app')

@section('content')
		<div class="container">
			<h1 class="display-3 text-center">Profiles</h1>
			@if($profiles->count() === 0)
				<div class="alert alert-info" role="alert">
				  Seems there are no profiles, yet.
				</div>
			@endif
			<div class="card-columns">
				@foreach($profiles as $profile) 
					<div class="card mr-1">
					  <img class="card-img-top img-fluid" src="{{ optional($profile->getFirstMedia('photos'))->getUrl('thumbnail') }}">
					  <div class="card-body">
					    <h5 class="card-title">{{ $profile->creator->name }}</h5>
					    <a href="{{ url('profiles', $profile) }}" class="btn btn-primary">Go to profile</a>
					  </div>
					</div>
				@endforeach	
			</div>
			 {{-- @include('components.popular-profiles') --}}

			 {{ $profiles->links() }}
		</div>
@endsection