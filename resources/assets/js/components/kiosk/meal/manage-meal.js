Vue.component('kiosk-manage-meals', {
	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'meals' && self.meals.length === 0) {
            	self.fetchMeals();
            	self.listenForNewMeals();
            }

            return true;
        });

 		const client = require('algoliasearch')(Spark.algolia.id, Spark.algolia.secret);

		this.algolia = client.initIndex('meals');
	},

	data () {
		return {
			meals: [],

			searchForm: new SparkForm({
				query: ''
			}),

			currentPage: null,
			lastPage: null,
			sortColumn: null,
			sortDirection: 'desc',

			creatingMeal: false,

			selectedMeal: null,

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
				this.searchMeals();
			}
		}
	},

	methods: {
		searchMeals () {
			this.algolia.search(this.searchForm.query, (err, content) => {
				if (err) {
					console.error(err);
					return;
				}
				
				this.meals = orderBy(content.hits, this.sortColumn, this.sortDirection);
			});
		},

		async edit(meal) {
			const { data } = await axios.get(meal.urls.api.show);

			this.selectedMeal = data;
		},

		async addMeal (meal) {
			this.creatingMeal = false;

			const { data } = await axios.get(meal.urls.api.show);

			
			this.meals.unshift(data);

		}, 

		clearSearch () {
			this.searchForm.query = '';
			this.meals = [];
			this.fetchMeals();
		},

		fetchNextMeals () {
			this.currentPage += this.currentPage;

			this.fetchMeals();
		},

		fetchPreviousMeals () {
			this.currentPage = this.currentPage -1;

			this.fetchMeals();
		},

		fetchMeals (page = null) {
			axios.get('/api/meals', {
				params: { 
					page: (page ? page : this.currentPage), 
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				},
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.meta.current_page;
				this.lastPage = data.meta.last_page;

				this.meals = data.data;
				this.listenForEvents();
			});
		},

		listenForNewMeals () {
			Echo.channel('App.Meal')
				.listen('Meal.MealCreated', (event) => {
					if (! this.meals.includes(event.meal)) {
						this.meals.unshift(event.meal);
					}
				})
		},

		listenForEvents () {
			const self = this;

			this.meals.forEach(function (meal) {
				Echo.channel(`App.Meal.${meal.id}`)
					.listen('Meal.MealUpdated', (event) => {
						const index = self.meals.findIndex(meal => parseInt(meal.id) === parseInt(event.meal.id))
						
						self.$set(self.meals, index, event.meal);
					})
					.listen('Meal.MealDeleted', (event) => {
						self.meals = self.meals.filter(meal => parseInt(meal.id) !== parseInt(event.meal.id));
					})
			});
		}
	}
});