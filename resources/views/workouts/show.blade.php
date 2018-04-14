@extends('layouts.app')

@section('content')
	<workout :workout="{{ $workout->toJson() }}" :user="user" inline-template>
		<div class="container text-center">
			<span class="title">
				<h1 class="display-3">{{ $workout->title }}</h1>
				<h3 class="font-weight-light lead">{{ $workout->summary }}</h3>	

				@if(optional(Auth::user()->currentWorkout)->is($workout))
					<p>{{ __("You're currently on this workout.") }}</p>
				@else
					<button type="submit" class="btn btn-success" @click="startWorkout">{{ __('Start workout') }}</button>	
				@endif	
			</span>	

			<hr class="divider"></hr>

			<span class="exercises">
				<h3>{{ __('Required equiptment') }}</h3>
				<ul class="list-group list-group-flush">
				  <li v-for="item in equipment" class="list-group-item">@{{ item }}</li>
				</ul>

				<h3>{{ __('Involved muscle(s)') }}</h3>
				<ul class="list-group list-group-flush">
				  <li v-for="muscle in muscles" class="list-group-item">@{{ muscle }}</li>
				</ul>
			</span>	

			<hr class="divider"></hr>

			<h3>What is this workout about</h3>
			<span class="body">
				{{ App\Markdown::parse($workout->body) }}
			</span>

			<hr class="divider"></hr>

			<h3>Media</h3>
			<div>
				<h4>Photos</h4>
				<div class="photos">
					@foreach($workout->getMedia('photos') as $photo)
						<img 
							onclick="Bus.$emit('PhotoModal.show', '{{ $photo->getUrl() }}')" 
							src="{{ $photo->getUrl('thumbnail') }}" 
							class="img-fluid clickable"
						></img>
					@endforeach
				</div>

				<h4>Videos</h4>
				<div class="videos">
					@foreach($workout->getMedia('videos') as $video) 
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

				<div class="documents">
					<h4>Documents</h3>

					<ul class="list-group list-group-flush">
						@foreach($workout->getMedia('documents') as $document) 
						<a href="{{ $document->getUrl() }}" class="list-group-item">
							{{ $document->name }}
						</a>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</workout>
@endsection