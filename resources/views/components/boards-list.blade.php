@foreach($boards as $board)
	<div class="card">
	  <div class="card-header">
	  	<h4 class="card-title">
		    <a href="{{ $board->link }}">
		    	{{ $board->name }}
		    </a>
		  	<span class="pull-right">
		    	<i class="fa fa-comment">Threads: </i>{{ $board->threads_count }}
		    </span>
	  	</h4>
	  </div>
	  <div class="card-block pl-2">
	    <p class="card-text lead">{{ $board->description }}</p>
	  </div>
	</div>
@endforeach