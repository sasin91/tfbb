@inject('popularity', 'App\Scores\Popularity')
@php
	$popular = $popularity->list('diets');
@endphp

<div class="card-body text-center">
	<h2>Popular diets</h2>
	@if($popular->isEmpty())
		<div class="alert alert-info" role="alert">
		  No diets has been visited, yet.
		</div>
	@else
		<ul class="list-group list-group-flush flex-column">
			@foreach($popular as $diet)
			  <li class="list-group-item d-flex justify-content-between align-items-center">
        		<a href="{{ $diet->link }}" class="list-group-item-action">
        			{{ $diet->title }}
        		</a>			   

        		 <span class="badge badge-primary badge-pill">
			    	<i class="fa fa-mouse-pointer">
			    		{{ $diet->score }}
			    	</i>
			    </span>			    
			  </li>
        	@endforeach
        </ul>
    @endif
</div>
