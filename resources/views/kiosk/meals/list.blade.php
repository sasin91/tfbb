<div class="card p-2">
	<div class="card-header">
		{{ __('Meals') }}
	</div>

	<div class="card-body">
		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th class="clickable" @click="sortBy('created_at')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'created_at'"></i>{{ __('Created') }}
		      </th>
		      <th class="clickable" @click="sortBy('name')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'name'"></i>{{ __('Name') }}
		      </th>
		      <th class="clickable" @click="sortBy('type')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'type'"></i>{{ __('Type') }}
		      </th>
		      <th scope="col">
		      	{{ __('Calories') }}
		      </th>
		      <th scope="col">
		      	{{ __('Protein') }}
		      </th>
		      <th scope="col">
		      	{{ __('Carbs') }}
		      </th>
		      <th scope="col">
		      	{{ __('Fat') }}
		      </th>
		      <th scope="col">
		      	{{ __('Foods') }}
		      </th>
		    </tr>
		  </thead>
		  <tbody>

		  	<tr v-for="meal in meals" :key="meal.id" @click="edit(meal)" 
		  		class="clickable" 
				data-toggle="tooltip" data-placement="top" :title="meal.description"
		  	>
		  		<td>@{{ meal.created_at | date }}</td>
		  		<td>@{{ meal.name }}</td>
		  		<td>@{{ meal.type }}</td>
		  		<td>@{{ meal.total_energy }}</td>
		  		<td>@{{ meal.total_proteins }}</td>
		  		<td>@{{ meal.total_carbohydrates }}</td>
		  		<td>@{{ meal.total_fats }}</td>
		  		<td>@{{ meal.foods_count }}</td>
		    </tr>
		  </tbody>
		</table>

		<nav aria-label="Page navigation">
		  <ul class="pagination">
		    <li class="page-item">
		      <a @click="fetchPreviousMeals()" class="page-link" aria-label="Previous">
		        <span aria-hidden="true">&laquo;</span>
		        <span class="sr-only">Previous</span>
		      </a>
		    </li>
		    <li v-for="page in pages" class="page-item">
		    	<a @click="fetchMeals(page)" class="page-link"> @{{ page }} </a>
		    </li>
		    <li class="page-item">
		      <a @click="fetchNextMeals()" class="page-link" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		        <span class="sr-only">Next</span>
		      </a>
		    </li>
		  </ul>
		</nav>
	</div>
</div>
