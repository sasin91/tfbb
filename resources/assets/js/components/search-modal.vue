<template>
	<div class="modal fade" id="global-search-modal">
		<div class="modal-dialog modal-dialog--wide" role="document">
			<div class="modal-content">
				<div class="modal-body">
					<form @submit.prevent>
				    	<input
				    		autofocus
				    		ref="input"
				    		v-model="query" 
				    		class="form-control form-control-lg mr-sm-2" 
				    		type="search" 
				    		placeholder="Search" 
				    		aria-label="Search"
				    	>
				  	</form>

				  	<div class="container">
				  		<ais-index :app-id="appId" :api-key="secret" index-name="boards" :query="query">
						    <ul class="list-group list-group-flush text-center">
						    	<ais-results>
						    		<div slot="header">
								    	<h2>Boards</h2>

								    	<ais-results-per-page-selector></ais-results-per-page-selector>
								    	<ais-stats></ais-stats>
								    </div>
								    
								    <hr class="divider"></hr>

						    		<template slot-scope="{ result }">
										<li class="list-group-item">
											<a :href="result.link">{{ result.name }}</a>
										</li>
						    		</template>
						    	</ais-results>
						    </ul>
						</ais-index>

						<ais-index :app-id="appId" :api-key="secret" index-name="threads" :query="query">
						    <ul class="list-group list-group-flush text-center">
						    	<ais-results>
						    		<div slot="header">
								    	<h2>Threads</h2>

						  				<ais-results-per-page-selector></ais-results-per-page-selector>
								    	<ais-stats></ais-stats>
								    </div>
								    
								    <hr class="divider"></hr>

						    		<template slot-scope="{ result }">
										<li class="list-group-item" data-toggle="tooltip" data-placement="top" :title="result.summary">
									  		<a :href="result.link">{{ result.title }}</a>
									  	</li>
						    		</template>
						    	</ais-results>
						    </ul>
						</ais-index>

						<ais-index :app-id="appId" :api-key="secret" index-name="workouts" :query="query">
						    <ul class="list-group list-group-flush text-center">
						    	<ais-results>
						    		<div slot="header">
								    	<h2>Workouts</h2>

						  				<ais-results-per-page-selector></ais-results-per-page-selector>
								    	<ais-stats></ais-stats>
								    </div>
								    
								    <hr class="divider"></hr>

						    		<template slot-scope="{ result }">
										<li class="list-group-item" data-toggle="tooltip" data-placement="top" :title="result.summary">
											<span class="badge">{{ result.level }}</span>
									  		<a :href="result.link">{{ result.title }}</a>
									  	</li>
						    		</template>
						    	</ais-results>
						    </ul>
					  </ais-index>

						<ais-index :app-id="appId" :api-key="secret" index-name="recordings" :query="query">
						    <ul class="list-group list-group-flush text-center">
						    	<ais-results>
						    		<div slot="header">
								    	<h2>Recordings</h2>

						  				<ais-results-per-page-selector></ais-results-per-page-selector>
								    	<ais-stats></ais-stats>
								    </div>
								    
								    <hr class="divider"></hr>

						    		<template slot-scope="{ result }">
										<li class="list-group-item" data-toggle="tooltip" data-placement="top" :title="result.summary">
											<span class="badge">{{ result.category }}</span>
									  		<a :href="result.link">{{ result.title }}</a>
									  	</li>
						    		</template>
						    	</ais-results>
						    </ul>
					  </ais-index>
				  	</div>	
				</div>

				<div class="modal-footer text-muted" style="display:block;">
					<p class="lead text-left">
						<i class="fa fa-info-circle">Pro tip:</i> You can tap escape anywhere to search.
					</p>

					<ais-powered-by />
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</template>

<script>
export default {

	data() {
	    return {
	    	visible: false,
	    	query: ''
	    }
	},

	mounted() {
		Bus.$on('search-modal.toggle', () => this.toggle());

		MouseTrap.bind(['space', 'esc', '?', 's', ':+%+s', ':+s'], () => this.toggle());
	},

	computed: {
		appId () {
	  		return Spark.algolia.id;
	  	},

	  	secret () {
	  		return Spark.algolia.secret;
	  	}
	},

	methods: {
	  	toggle () {
	  		if (this.visible) {
	  			$('#global-search-modal').modal('hide');
	  		} else {
		  		$('#global-search-modal').modal('show');

		  		this.$refs.input.focus();
	  		}
	  	},

	  	value (value) {
	  		if (typeof value === 'object' && value.hasOwnProperty('value')) {
	  			return value.value;
	  		}

	  		return value;
	  	}
	}
}
</script>