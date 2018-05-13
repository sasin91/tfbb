<template>
	<div>
	  	<multiselect 
	  		v-model="selectedFoods" 
	  		id="food-search" 
	  		label="name" 
	  		track-by="ndbno"
	  		placeholder="Search for food(s)" 
	  		open-direction="bottom" 
	  		:options="foods" 
	  		:multiple="true" 
	  		:searchable="true" 
	  		:loading="loading" 
	  		:internal-search="true" 
	  		:clear-on-select="false"
	  		:close-on-select="false" 
	  		:options-limit="300"
	  		:limit="3" 
	  		:limit-text="limitText" 
	  		:max-height="600" 
	  		:show-no-results="true" 
	  		:hide-selected="true" 
	  		@search-change="search"
	  	>
	    	<template slot="clear" slot-scope="props">
	      	<div class="multiselect__clear" v-if="selectedFoods.length" @mousedown.prevent.stop="clearAll(props.search)"></div>
	    	</template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
	  	</multiselect>
	</div>
</template>

<script>
	import Multiselect from 'vue-multiselect'

	export default {
		components: {Multiselect},

		data () {
			return {
				selectedFoods: [],

				page: 0,
				limit: 50,
				loading: false,
				query: '',

				foods: [],
			}
		},
		
		methods: {
			async search (value) {
				if (this.loading) {
					return;
				}

				if (! value || value.length === 0) {
					return;
				}

				this.loading = true;

				const { data } = await axios({
					method: 'GET',
					url: '/api/ndb/search',
					params: { query:value, page:this.page, perPage:this.limit }
				});

				this.foods = data;

				this.loading = false;
			},

		    limitText (count) {
		      return `and ${count} other foods...`
		    },

		    clearAll () {
		      this.selectedFoods = []
		    }
		}
	}
</script>