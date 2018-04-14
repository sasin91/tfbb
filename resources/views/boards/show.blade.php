@extends('layouts.app')

@section('content')
	<div class="container">
		<show-board :board="{{ $board->toJson() }}" inline-template>
			<div class="board">
				<h1 class="text-center">@{{ board.name }}</h1>
				<p class="h4 lead text-center">@{{ board.description }}</p>

				<hr class="divider"></hr>
				<h3 class="text-center">Threads</h3>

				<button @click="creatingNewThread = ! creatingNewThread" class="btn btn-outline-primary">
					New thread
				</button>

				<new-thread-card :board="board" @created="redirectToThread" v-show="creatingNewThread">
				</new-thread-card>

				<section v-if="threads.length > 0">
					<p>Displaying @{{ threads.length }} out of @{{ board.threads_count }} threads.</p>
					<thread-card v-for="thread in threads" :key="thread.hashid" :thread="thread"></thread-card>
				</section>
				
				<div v-else class="alert alert-info" role="alert">
					There are no threads at the moment.
				</div>

				<button 
					v-else
					@click="fetchMoreThreads" 
					type="button" 
					class="btn btn-outline-primary rounded-circle align-self-center"
				>Load more</button>
			</div>
		</show-board>
	</div>
@endsection