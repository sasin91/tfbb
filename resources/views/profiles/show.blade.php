@extends('layouts.app')

@section('content')
		<div class="container text-center">
			<div class="title">
				<h1 class="display-3">
					Hello, i am {{ $profile->creator->name }}.
				</h1>


				@if($profile->currentWorkout)
					<p class="lead">
						I'm currently following <a href="{{ url('workouts', $profile->currentWorkout) }}">
							{{ $profile->currentWorkout->title }}
						</a>
					</p>
				@endif

				@if(Auth::user()->is($profile->creator))
					<button type="button" class="btn btn-link">
						<a class="display-4" href="{{ url('settings#profile') }}">Edit</a>
					</button>
				@endif
			</div>
			<hr class="divider"></hr>
			<article class="body">
				<section class="goals">
					<h3>My goals</h3>

					{{ App\Markdown::parse($profile->goals) }}
				</section>

				<section class="story">
					<h3>My story</h3>

					{{ App\Markdown::parse($profile->story) }}
				</section>
			</article>

			<hr class="divider"></hr>

			@if($profile->getMedia('photos')->isNotEmpty())
				<h3>My photo(s)</h3>
				<div class="photos">
					@foreach($profile->getMedia('photos') as $photo)
						<img 
							onclick="Bus.$emit('PhotoModal.show', '{{ $photo->getUrl() }}')" 
							src="{{ $photo->getUrl('thumbnail') }}" 
							class="img-fluid clickable"
						></img>
					@endforeach
				</div>
			@endif

			@if($profile->getMedia('videos')->isNotEmpty())
				<h3>My video(s)</h3>
				<div class="videos">
					@foreach($profile->getMedia('videos') as $video) 
						@if ($video->getUrl('thumbnail'))
							<video controls preload="auto" width="368" height="323" poster="{{ $video->getUrl('thumbnail') }}">
						@else
							<video controls preload="auto" width="368" height="323">
						@endif			
							  <source src="{{ $video->getUrl() }}" type="video/mp4">
							  Your browser does not support the video tag.
							</video>
					@endforeach
				</div>
			@endif
		</div>
@endsection