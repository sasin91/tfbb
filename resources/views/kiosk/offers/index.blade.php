	<div class="card">
		<div class="card-header">
			Offers
		</div>
	  	<div class="card-body">
			<table class="table table-hover">
			  <thead>
			    <tr>
			      <th class="clickable" @click="sortBy('name')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'name'"></i>{{ __('Category') }}
			      </th>
			      <th class="clickable" @click="sortBy('discount')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'discount'"></i>{{ __('Title') }}
			      </th>
			      <th class="clickable" @click="sortBy('view')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'view'"></i>{{ __('View') }}
			      </th>
			      <th class="clickable" @click="sortBy('created_at')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'created_at'"></i>{{ __('Created') }}
			      </th>
			    </tr>
			  </thead>
			  <tbody>
			  	<tr 
			  		v-for="offer in offers" :key="offer.id" class="clickable" @click="showOffer(offer)" 
			  		data-toggle="tooltip" 
			  		data-placement="top" 
			  		:discount="offer.summary"
			  	>
			  		<td>@{{ offer.name }}</td>
			  		<td>@{{ offer.discount }}</td>
			  		<td>@{{ offer.view }}</td>
			  		<td>@{{ offer.created_at | date }}</td>
			    </tr>
			  </tbody>
			</table>
	  	</div>
	  	<div class="card-footer">
			<nav aria-label="Page navigation">
			  <ul class="pagination">
			    <li class="page-item">
			      <a @click="fetchPreviousOffers()" class="page-link" aria-label="Previous">
			        <span aria-hidden="true">&laquo;</span>
			        <span class="sr-only">Previous</span>
			      </a>
			    </li>
			    <li v-for="page in pages" class="page-item">
			    	<a @click="fetchOffers(page)" class="page-link"> @{{ page }} </a>
			    </li>
			    <li class="page-item">
			      <a @click="fetchNextOffers()" class="page-link" aria-label="Next">
			        <span aria-hidden="true">&raquo;</span>
			        <span class="sr-only">Next</span>
			      </a>
			    </li>
			  </ul>
			</nav>
	  	</div>
	</div>
