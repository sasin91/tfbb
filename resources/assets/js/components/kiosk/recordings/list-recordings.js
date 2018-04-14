Vue.component('kiosk-list-recordings', {
	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'recordings' && self.recordings.length === 0) {
            	self.fetchRecordings();
            	self.listenForNewRecordings();
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

			recordings: []
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

			this.fetchRecordings();
		},

		fetchNextRecordings () {
			this.currentPage += this.currentPage;

			this.fetchRecordings();
		},

		fetchPreviousRecordings () {
			this.currentPage = this.currentPage -1;

			this.fetchRecordings();
		},

		fetchRecordings (page = null) {
			axios.get('/api/recordings', {
				params: { 
					perPage: 5,
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

		showRecording (recording) {
			this.$parent.$refs.manageRecordingModal.show(recording);
		},

		listenForNewRecordings () {
			Echo.channel('App.Recording')
				.listen('Recording.RecordingCreated', (event) => {
					if (! this.recordings.includes(event.recording)) {
						this.recordings.unshift(event.recording);
					}
				})
		},

		listenForEvents () {
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