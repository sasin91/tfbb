@inject('popularity', 'App\Scores\Popularity')
@php
	$popular = $popularity->list('threads');
@endphp

<div class="card-body text-center">
	<h2>Popular threads</h2>
	@if($popular->isEmpty())
		<div class="alert alert-info" role="alert">
		  No threads has been visited, yet.
		</div>
	@else
		<div class="list-group list-group-flush">
			@foreach($popular as $thread)
			  <li class="list-group-item d-flex justify-content-between align-items-center">
        		<a href="{{ $thread->link }}" class="list-group-item-action">
        			{{ $thread->name }}
        		</a>			   

        		 <span class="badge badge-primary badge-pill">
			    	<i class="fa fa-mouse-pointer">
			    		{{ $thread->score }}
			    	</i>
			    </span>			    
			  </li>
        	@endforeach
        </div>
    @endif
</div>
