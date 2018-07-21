Vue.component('kiosk-manage-recordings', {
	mounted() {
		const self = this;

		Bus.$on('sparkHashChanged', function (hash, parameters) {
			if (hash == 'recordings' && self.recordings.length === 0) {
				self.fetchRecordings();
				self.listenForNewRecordings();
			}

			return true;
		});

		const client = require('algoliasearch')(Spark.algolia.id, Spark.algolia.secret);

		this.algolia = client.initIndex('recordings');
	},

	data() {
		return {
			recordings: [],

			searchForm: new SparkForm({
				query: ''
			}),

			currentPage: null,
			lastPage: null,
			sortColumn: null,
			sortDirection: 'desc',

			creatingRecording: false,

			selectedRecording: null,

			algolia: null
		}
	},

	computed: {
		pages() {
			return Array.from(Array(this.lastPage + 1).keys()).slice(1);
		},

		sortIcon() {
			return this.sortDirection === 'asc' ? 'fa-arrow-down' : 'fa-arrow-up';
		}
	},

	watch: {
		'searchForm.query': {
			handler: function (value) {
				this.searchRecordings();
			}
		}
	},

	methods: {
		searchRecordings() {
			this.algolia.search(this.searchForm.query, (err, content) => {
				if (err) {
					console.error(err);
					return;
				}

				this.recordings = orderBy(content.hits, this.sortColumn, this.sortDirection);
			});
		},

		async edit(recording) {
			const { data } = await axios.get(recording.urls.api.show);

			this.selectedRecording = data;
		},

		async addRecording(recording) {
			this.creatingRecording = false;

			const { data } = await axios.get(recording.urls.api.show);


			this.recordings.unshift(data);

		},

		clearSearch() {
			this.searchForm.query = '';
			this.recordings = [];
			this.fetchRecordings();
		},

		fetchNextRecordings() {
			this.currentPage += this.currentPage;

			this.fetchRecordings();
		},

		fetchPreviousRecordings() {
			this.currentPage = this.currentPage - 1;

			this.fetchRecordings();
		},

		fetchRecordings(page = null) {
			axios.get('/api/recordings', {
				params: {
					page: (page ? page : this.currentPage),
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				},
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.meta.current_page;
				this.lastPage = data.meta.last_page;

				this.recordings = data.data;
				this.listenForEvents();
			});
		},

		listenForNewRecordings() {
			Echo.channel('App.Recording')
				.listen('Recording.RecordingCreated', (event) => {
					if (!this.recordings.includes(event.recording)) {
						this.recordings.unshift(event.recording);
					}
				})
		},

		listenForEvents() {
			const self = this;

			this.recordings.forEach(function (recording) {
				Echo.channel(`App.Recording.${recording.id}`)
					.listen('Recording.RecordingUpdated', (event) => {
						const index = self.recordings.findIndex(recording => parseInt(recording.id) === parseInt(event.recording.id))

						self.$set(self.recordings, index, event.recording);
					})
					.listen('Recording.RecordingDeleted', (event) => {
						self.recordings = self.recordings.filter(recording => parseInt(recording.id) !== parseInt(event.recording.id));
					})
			});
		}
	}
});