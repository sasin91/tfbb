<div class="card p-2">
	<div class="card-header">
		{{ __('Diets') }}
	</div>

	<div class="card-body">
		<table class="table table-hover">
		  <thead>
		    <tr>
		      <th class="clickable" @click="sortBy('title')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'title'"></i>{{ __('Title') }}
		      </th>
		      <th class="clickable" @click="sortBy('goal')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'goal'"></i>{{ __('Goal') }}
		      </th>
		      <th class="clickable" @click="sortBy('style')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'style'"></i>{{ __('Style') }}
		      </th>
		      <th class="clickable" scope="col">
		      	{{ __('Meals') }}
		      </th>
		      <th class="clickable" @click="sortBy('created_at')" scope="col">
		      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'created_at'"></i>{{ __('Created at') }}
		      </th>
		    </tr>
		  </thead>
		  <tbody>
		  	<tr v-for="diet in diets" :key="diet.id" @click="edit(diet)" class="clickable">
		  		<td>@{{ diet.title }}</td>
		  		<td>@{{ diet.goal }}</td>
		  		<td>@{{ diet.style }}</td>
		  		<td>@{{ diet.meals }}</td>
		  		<td>@{{ diet.created_at | date }}</td>
		    </tr>
		  </tbody>
		</table>

		<nav aria-label="Page navigation">
		  <ul class="pagination">
		    <li class="page-item">
		      <a @click="fetchPreviousDiets()" class="page-link" aria-label="Previous">
		        <span aria-hidden="true">&laquo;</span>
		        <span class="sr-only">Previous</span>
		      </a>
		    </li>
		    <li v-for="page in pages" class="page-item">
		    	<a @click="fetchDiets(page)" class="page-link"> @{{ page }} </a>
		    </li>
		    <li class="page-item">
		      <a @click="fetchNextDiets()" class="page-link" aria-label="Next">
		        <span aria-hidden="true">&raquo;</span>
		        <span class="sr-only">Next</span>
		      </a>
		    </li>
		  </ul>
		</nav>
	</div>
</div>
