import { orderBy } from 'lodash';
import { fileFromUrl } from '../../helpers'

Vue.component('kiosk-manage-diets', {
	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'diets' && self.diets.length === 0) {
            	self.fetchDiets();
            	self.listenForNewDiets();
            }

            return true;
        });

		const client = require('algoliasearch')(Spark.algolia.id, Spark.algolia.secret);

		this.algolia = client.initIndex('diets');
	},

	destroyed () {
		this.algolia = null;
	},

	data () {
		return {
			currentPage: null,
			lastPage: null,
			sortColumn: null,
			sortDirection: 'desc',

			creatingDiet: false,

			selectedDiet: null,

			diets: [],

			searchForm: new SparkForm({
				query: ''
			}),

			algolia: null
		}
	},

	computed: {
		pages () {
			return Array.from(Array(this.lastPage+1).keys()).slice(1);
		},

		sortIcon () {
			return this.sortDirection === 'asc' ? 'fa-arrow-down' : 'fa-arrow-up';
		}
	},

	watch: {
		'searchForm.query': {
			handler: function (value) {
				this.searchDiets();
			}
		}
	},

	methods: {
		async edit(diet) {
			const { data } = await axios.get(diet.urls.api.show);

			this.selectedDiet = data;
		},

		sortBy (column) {
			if (column == this.sortColumn) {
				this.sortDirection = (this.sortDirection === 'desc') ? 'asc' : 'desc';
			}

			this.sortColumn = column;

			if (this.searchForm.query.length > 0) {
				this.searchDiets();
			} else {
				this.fetchDiets();
			}
		},

		addDiet (diet) {
			this.creatingDiet = false;
			this.diets.unshift(diet);
		}, 

		searchDiets () {
			this.algolia.search(this.searchForm.query, (err, content) => {
				if (err) {
					console.error(err);
					return;
				}
				
				this.diets = orderBy(content.hits, this.sortColumn, this.sortDirection);
			});
		},

		clearSearch () {
			this.searchForm.query = '';
			this.diets = [];
			this.fetchDiets();
		},

		fetchNextDiets () {
			this.currentPage += this.currentPage;

			this.fetchDiets();
		},

		fetchPreviousDiets () {
			this.currentPage = this.currentPage -1;

			this.fetchDiets();
		},

		fetchDiets (page = null) {
			axios.get('/api/diets', {
				params: { 
					page: (page ? page : this.currentPage), 
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				},
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.meta.current_page;
				this.lastPage = data.meta.last_page;

				this.diets = data.data;
				this.listenForEvents();
			});
		},

		listenForNewDiets () {
			Echo.channel('App.Diet')
				.listen('Diet.DietCreated', (event) => {
					if (! this.diets.includes(event.diet)) {
						this.diets.unshift(event.diet);
					}
				})
		},

		listenForEvents () {
			const self = this;

			this.diets.forEach(function (diet) {
				Echo.channel(`App.Diet.${diet.id}`)
					.listen('Diet.DietUpdated', (event) => {
						const index = self.diets.findIndex(diet => parseInt(diet.id) === parseInt(event.diet.id))
						
						self.$set(self.diets, index, event.diet);
					})
					.listen('Diet.DietDeleted', (event) => {
						self.diets = self.diets.filter(diet => parseInt(diet.id) !== parseInt(event.diet.id));
					})
			});
		}
	}
})