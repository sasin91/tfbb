import ManageWorkoutModal from './manage-workout-modal.vue';
import NewWorkoutModal from './new-workout-modal.vue';

Vue.component('kiosk-manage-workouts', {
	components: {ManageWorkoutModal, NewWorkoutModal},

	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'workouts' && self.workouts.length === 0) {
            	self.fetchWorkouts();
            	self.listenForNewWorkouts();
            }

            return true;
        });
	},

	data () {
		return {
			currentPage: null,
			lastPage: null,
			sortColumn: null,
			sortDirection: 'desc',

			workouts: [],

			searchForm: new SparkForm({
				query: ''
			})
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

	methods: {
		sortBy (column) {
			if (column == this.sortColumn) {
				this.sortDirection = (this.sortDirection === 'desc') ? 'asc' : 'desc';
			}

			this.sortColumn = column;

			if (this.searchForm.query.length > 0) {
				this.searchWorkouts();
			} else {
				this.fetchWorkouts();
			}
		},

		searchWorkouts () {
			axios.get('/api/search-workouts', {
				params: Object.assign({}, { 
					page: this.currentPage, 
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				}, this.searchForm.query),
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.current_page;
				this.lastPage = data.last_page;

				this.workouts = data.data;
				this.listenForEvents();
			});
		},

		fetchNextWorkouts () {
			this.currentPage += this.currentPage;

			this.fetchWorkouts();
		},

		fetchPreviousWorkouts () {
			this.currentPage = this.currentPage -1;

			this.fetchWorkouts();
		},

		fetchWorkouts (page = null) {
			axios.get('/api/workouts', {
				params: { 
					page: (page ? page : this.currentPage), 
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				},
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.current_page;
				this.lastPage = data.last_page;

				this.workouts = data.data;
				this.listenForEvents();
			});
		},

		showWorkout (workout) {
			this.$refs.manageWorkoutModal.show(workout);
		},

		showNewWorkoutModal () {
			this.$refs.newWorkoutModal.show();
		},

		listenForNewWorkouts () {
			Echo.channel('App.Workout')
				.listen('Workout.WorkoutCreated', (event) => {
					if (! this.workouts.includes(event.workout)) {
						this.workouts.unshift(event.workout);
					}
				})
		},

		listenForEvents () {
			const self = this;

			this.workouts.forEach(function (workout) {
				Echo.channel(`App.Workout.${workout.id}`)
					.listen('Workout.WorkoutUpdated', (event) => {
						const index = self.workouts.findIndex(workout => parseInt(workout.id) === parseInt(event.workout.id))
						
						self.$set(self.workouts, index, event.workout);
					})
					.listen('Workout.WorkoutDeleted', (event) => {
						self.workouts = self.workouts.filter(workout => parseInt(workout.id) !== parseInt(event.workout.id));
					})
			});
		}
	}
})