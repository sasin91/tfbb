@inject('popularity', 'App\Scores\Popularity')
@php
	$popular = $popularity->list('workouts');
@endphp

<div class="card-body text-center">
	<h2>Popular workouts</h2>
	@if($popular->isEmpty())
		<div class="alert alert-info" role="alert">
		  No workouts has been visited, yet.
		</div>
	@else
		<ul class="list-group list-group-flush flex-column">
			@foreach($popular as $workout)
			  <li class="list-group-item d-flex justify-content-between align-items-center">
        		<a href="{{ $workout->link }}" class="list-group-item-action">
        			{{ $workout->title }}
        		</a>			   

        		 <span class="badge badge-primary badge-pill">
			    	<i class="fa fa-mouse-pointer">
			    		{{ $workout->score }}
			    	</i>
			    </span>			    
			  </li>
        	@endforeach
        </ul>
    @endif
</div>
