@extends('layouts.app')

@section('content')
		<div class="container">
			<h1 class="display-3 text-center">Boards</h1>
			
			<div class="col-md-12">
				@include('components.boards-list', $boards)

				{{ $boards->links('pagination::bootstrap-4') }}
			</div>

			<div class="card mx-3">
				@include('components.popular-boards')
			</div>
		</div>
@endsection