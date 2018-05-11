<template>
	<div>
		<label class="typo__label" for="food-search">Async multiselect</label>
	  	<multiselect 
	  		v-model="selectedFoods" 
	  		id="food-search" 
	  		label="name" 
	  		track-by="ndbno"
	  		placeholder="Type to search" 
	  		open-direction="bottom" 
	  		:options="foods" 
	  		:multiple="true" 
	  		:searchable="true" 
	  		:loading="loading" 
	  		:internal-search="false" 
	  		:clear-on-select="false"
	  		:close-on-select="false" 
	  		:options-limit="300"
	  		:limit="3" 
	  		:limit-text="limitText" 
	  		:max-height="600" 
	  		:show-no-results="false" 
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

				loading: false,
				foods: [],

				client: null
			}
		},

		computed: {
			apiKey () {
				return Spark.ndb.key;
			}
		},

		mounted () {
			this.client = axios.create({
			  baseURL: 'https://api.nal.usda.gov/ndb/',
			  timeout: 1000,
			  headers: { 'X-Api-Key': apiKey, 'Content-Type':'application/json', 'Accept':'application/json' }
			});
		},

		destroyed () {
			this.client = null;
		},

		methods: {
			async search (value, page = 0, limit = 25) {
				this.loading = true;

				const { list } = await this.client.get('/search', { q:value, offset:page, max:limit });

				this.foods = list.item;

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