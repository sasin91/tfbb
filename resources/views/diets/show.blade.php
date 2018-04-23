@extends('layouts.app')

@section('content')
	<diet :diet="{{ $diet->toJson() }}" :user="user" inline-template>
		<div class="container text-center">
			<span class="title">
				<h1 class="display-3">{{ $diet->title }}</h1>
				<h3 class="font-weight-light lead">{{ $diet->summary }}</h3>	

				@if(optional(Auth::user()->currentDiet)->is($diet))
					<p>{{ __("You're currently on this diet.") }}</p>
				@else
					<button type="submit" class="btn btn-success" @click="startDiet">{{ __('Start diet') }}</button>	
				@endif	
			</span>	

			<hr class="divider"></hr>

			<span class="exercises">
				<h3>{{ __('Allergy warnings') }}</h3>
				<ul class="list-group list-group-flush">
				  <li v-for="allergy in allergies" class="list-group-item">@{{ allergy.name }}</li>
				</ul>

				<h3>{{ __('Foods') }}</h3>
				<ul class="list-group list-group-flush">
				  <li v-for="food in foods" class="list-group-item">@{{ food.name }}</li>
				</ul>
			</span>	

			<hr class="divider"></hr>

			<h3>What is this diet about</h3>
			<span class="body">
				{{ App\Markdown::parse($diet->body) }}
			</span>

			<hr class="divider"></hr>

			<h3>Media</h3>
			<div>
				<h4>Photos</h4>
				<div class="photos">
					@foreach($diet->getMedia('photos') as $photo)
						<img 
							onclick="Bus.$emit('PhotoModal.show', '{{ $photo->getUrl() }}')" 
							src="{{ $photo->getUrl('thumbnail') }}" 
							class="img-fluid clickable"
						></img>
					@endforeach
				</div>

				<h4>Videos</h4>
				<div class="videos">
					@foreach($diet->getMedia('videos') as $video) 
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
						@foreach($diet->getMedia('documents') as $document) 
						<a href="{{ $document->getUrl() }}" class="list-group-item">
							{{ $document->name }}
						</a>
						@endforeach
					</ul>
				</div>
			</div>
		</div>
	</diet>
@endsection