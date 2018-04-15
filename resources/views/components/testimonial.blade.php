<div class="shadow-lg p-3 mx-1 bg-light rounded">
	<img class="mx-auto rounded-circle" height="200" width="200" src="{{ $testimonial->reviewer_photo_url }}" alt="{{ $testimonial->reviewer }}">
	<div class="card-block">
		<h4 class="card-title">{{ $testimonial->title }}</h4>
		<p class="card-text">{{ $testimonial->body }}</p>
	</div>

	<hr class="divider"></hr>

	<footer>
		- {{ $testimonial->reviewer }}, {{ $testimonial->created_at->diffForHumans() }}
	</footer>
</div>