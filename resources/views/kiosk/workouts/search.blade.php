<form method="GET" @submit.prevent>
	<div class="form-group">
		<div class="input-group">
 				<input 
 					v-model="searchForm.query"
 				  	name="search"
 				  	type="search" 
 				  	class="form-control" 
 				  	placeholder="{{ __('Search for workouts...') }}" 
 				  	aria-label="{{ __('Search for workouts...') }}" 
 				  	aria-describedby="submit-search"
				  	>  			<div class="input-group-append">
	        	<button 
	        		@click="searchWorkouts()"
	        		id="submit-search" 
	        		class="btn btn-default" 
	        		type="submit"
	        	>Search</button>
			</div>
		</div>
	</div>
</form>