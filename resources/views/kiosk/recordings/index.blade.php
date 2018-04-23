<kiosk-list-recordings inline-template>
	<div class="card p-2">
		<div class="card-header">
			Recordings
		</div>
	  	<div class="card-body">
			<table class="table table-hover">
			  <thead>
			    <tr>
			      <th class="clickable" @click="sortBy('category')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'category'"></i>{{ __('Category') }}
			      </th>
			      <th class="clickable" @click="sortBy('title')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'title'"></i>{{ __('Title') }}
			      </th>
			      <th class="clickable" @click="sortBy('created_at')" scope="col">
			      	<i class="fa" :class="sortIcon" v-show="sortColumn === 'created_at'"></i>{{ __('Created') }}
			      </th>
			    </tr>
			  </thead>
			  <tbody>
			  	<tr 
			  		v-for="recording in recordings" :key="recording.id" class="clickable" @click="showRecording(recording)" 
			  		data-toggle="tooltip" 
			  		data-placement="top" 
			  		:title="recording.summary"
			  	>
			  		<td>@{{ recording.category }}</td>
			  		<td>@{{ recording.title }}</td>
			  		<td>@{{ recording.created_at | date }}</td>
			    </tr>
			  </tbody>
			</table>
	  	</div>
	  	<div class="card-footer">
			<nav aria-label="Page navigation">
			  <ul class="pagination">
			    <li class="page-item">
			      <a @click="fetchPreviousRecordings()" class="page-link" aria-label="Previous">
			        <span aria-hidden="true">&laquo;</span>
			        <span class="sr-only">Previous</span>
			      </a>
			    </li>
			    <li v-for="page in pages" class="page-item">
			    	<a @click="fetchRecordings(page)" class="page-link"> @{{ page }} </a>
			    </li>
			    <li class="page-item">
			      <a @click="fetchNextRecordings()" class="page-link" aria-label="Next">
			        <span aria-hidden="true">&raquo;</span>
			        <span class="sr-only">Next</span>
			      </a>
			    </li>
			  </ul>
			</nav>
	  	</div>
	</div>
</kiosk-list-recordings>