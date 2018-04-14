@inject('popularity', 'App\Scores\Popularity')
@php
	$popular = $popularity->list('boards');
@endphp

<div class="card-body text-center">
	<h2>Popular boards</h2>
	@if($popular->isEmpty())
		<div class="alert alert-info" role="alert">
		  No boards has been visited, yet.
		</div>
	@else
		<div class="list-group list-group-flush">
			@foreach($popular as $board)
			  <li class="list-group-item d-flex justify-content-between align-items-center">
        		<a href="{{ $board->link }}" class="list-group-item-action">
        			{{ $board->name }}
        		</a>			   

        		 <span class="badge badge-primary badge-pill">
			    	<i class="fa fa-mouse-pointer">
			    		{{ $board->score }}
			    	</i>
			    </span>			    
			  </li>
        	@endforeach
        </div>
    @endif
</div>
