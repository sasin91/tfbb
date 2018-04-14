@inject('popularity', 'App\Scores\Popularity')
@php
	$popular = $popularity->list('profiles');
@endphp

<div class="card-body text-center">
	<h2>Popular profiles</h2>
	@if($popular->isEmpty())
		<div class="alert alert-info" role="alert">
		  No profiles has been visited, yet.
		</div>
	@else
		<ul class="list-group list-group-flush flex-column">
			@foreach($popular as $profile)
			  <li class="list-group-item d-flex justify-content-between align-items-center">
        		<a href="{{ $profile->link }}" class="list-group-item-action">
        			{{ $profile->title }}
        		</a>			   

        		 <span class="badge badge-primary badge-pill">
			    	<i class="fa fa-mouse-pointer">
			    		{{ $profile->score }}
			    	</i>
			    </span>			    
			  </li>
        	@endforeach
        </ul>
    @endif
</div>
