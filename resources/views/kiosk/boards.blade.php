<kiosk-manage-boards inline-template>
	<div>
		<h1 class="text-center">Boards</h1>

		<form method="GET" @submit.prevent>
			<div class="form-group">
				<div class="input-group">
	  				<input 
	  					v-model="searchForm.query"
	  				  	name="search"
	  				  	type="search" 
	  				  	class="form-control" 
	  				  	placeholder="{{ __('Search for boards...') }}" 
	  				  	aria-label="{{ __('Search for boards...') }}" 
	  				  	aria-describedby="submit-search"
  				  	>

		  			<div class="input-group-append">
			        	<button 
			        		@click="fetchBoards({ search: searchForm.query })"
			        		id="submit-search" 
			        		class="btn btn-default" 
			        		type="submit"
			        	>Search</button>
					</div>
				</div>
			</div>
		</form>

		<div class="btn-group" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-link pull-right" @click="showNewBoardModal">New</button>
		</div>

		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th scope="col">{{ __('Published') }}</th>
		      <th scope="col">{{ __('Name') }}</th>
		      <th scope="col">{{ __('Created') }}</th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr v-for="board in boards" :key="board.id" @click="showBoard(board)" class="clickable">
		  		<td>
		  			<i v-if="board.published" class="fa fa-check"></i>
		  			<i v-else class="fa fa-times"></i>
		  		</td>
		  		<td>@{{ board.name }}</td>
		  		<td>@{{ board.created_at | date }}</td>
		    </tr>
		  </tbody>
		</table>

		<new-board-modal ref="newBoardModal"></new-board-modal>
		<manage-board-modal ref="manageBoardModal"></manage-board-modal>
	</div>
</kiosk-manage-boards>