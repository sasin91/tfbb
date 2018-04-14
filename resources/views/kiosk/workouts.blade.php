<kiosk-manage-workouts inline-template>
	<div>
		<h1 class="text-center">Workouts</h1>

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
  				  	>

		  			<div class="input-group-append">
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

		<div class="btn-group" role="group" aria-label="Basic example">
			<button type="button" class="btn btn-link pull-right" @click="showNewWorkoutModal">New</button>
		</div>

		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th class="clickable" @click="sortBy('title')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'title'"></i>{{ __('Title') }}
		      </th>
		      <th class="clickable" @click="sortBy('level')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'level'"></i>{{ __('Level') }}
		      </th>
		      <th class="clickable" @click="sortBy('type')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'type'"></i>{{ __('Type') }}
		      </th>
		      <th class="clickable" @click="sortBy('created_at')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'created_at'"></i>{{ __('Created at') }}
		      </th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr v-for="workout in workouts" :key="workout.id" @click="showWorkout(workout)" class="clickable">
		  		<td>@{{ workout.title }}</td>
		  		<td>@{{ workout.level }}</td>
		  		<td>@{{ workout.type }}</td>
		  		<td>@{{ workout.created_at | date }}</td>
		    </tr>
		  </tbody>
		</table>

		<nav aria-label="Page navigation">
		  <ul class="pagination">
		    <li class="page-item">
		      <a @click="fetchPreviousWorkouts()" class="page-link" aria-label="Previous">
		        <span aria-hidden="true">&laquo;</span>
		        <span class="sr-only">Previous</span>
		      </a>
		    </li>
		    <li v-for="page in pages" class="page-item">
		    	<a @click="fetchWorkouts(page)" class="page-link"> @{{ page }} </a>
		    </li>
		    <li class="page-item">
		      <a @click="fetchNextWorkouts()" class="page-link" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		        <span class="sr-only">Next</span>
		      </a>
		    </li>
		  </ul>
		</nav>
		<new-workout-modal ref="newWorkoutModal"></new-workout-modal>
		<manage-workout-modal ref="manageWorkoutModal" :config="{{ json_encode(config('training')) }}"></manage-workout-modal>
	</div>
</kiosk-manage-workouts>