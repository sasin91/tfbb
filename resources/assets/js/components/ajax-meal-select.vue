<template>
	<multiselect 
		v-model="selectedMeals" 
		id="meal-search" 
		label="name" 
		track-by="objectID"
		placeholder="Find meals..." 
		open-direction="bottom" 
		:options="meals" 
		:multiple="true" 
		:searchable="true" 
		:loading="loading" 
		:internal-search="true" 
		:clear-on-select="false"
		:close-on-select="false" 
		:options-limit="300"
		:limit="10" 
		:limit-text="limitText" 
		:max-height="600" 
		:show-no-results="false" 
		:hide-selected="true" 
		@search-change="searchMeals"
	>
	 	<template slot="clear" slot-scope="props">
	   	<div class="multiselect__clear" v-if="selectedMeals.length" @mousedown.prevent.stop="clearSelectMeals(props.search)"></div>
	 	</template><span slot="noResult">Oops! No elements found. Consider changing the search query.</span>
	</multiselect>
</template>

<script>
	import Multiselect from 'vue-multiselect'

	export default {
		components: {Multiselect},

		data () {
			return {
				loading: false,

				meals: [],
				selectedMeals: [],

				algolia: null
			}
		},

		mounted () {
			const client = require('algoliasearch')(Spark.algolia.id, Spark.algolia.secret);

			this.algolia = client.initIndex('meals');
		},

		destroyed () {
			this.algolia = null;
		},

		methods: {
			limitText (count) {
			  return `and ${count} other meal(s)...`
			},

			searchMeals (query) {
				this.algolia.search(query, (err, content) => {
					if (err) {
						console.error(err);
					}

					this.meals = content.hits;
				});
			},

			clearSelectedMeals () {
				this.selectedMeals = [];
			}
		}
	}
</script>