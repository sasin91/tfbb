@extends('layouts.app')

@section('content')
	<div class="container text-center">
		<span class="title">
			<h1 class="display-3">{{ $recording->title }}</h1>
			<h3 class="font-weight-light lead">{{ $recording->summary }}</h3>	
		</span>	

		<hr class="divider"></hr>

		<h3>What is this recording about</h3>
		<span class="body">
			{{ App\Markdown::parse($recording->body) }}
		</span>

		<hr class="divider"></hr>

		<h4>Videos</h4>
		<div class="videos">
			@foreach($recording->getMedia('videos') as $video) 
				<video controls preload="auto" width="800" height="600">
					<source src="{{ $video->getUrl() }}" type="video/mp4">
					Your browser does not support the video tag.
				</video>
			@endforeach
		</div>
	</div>
@endsection