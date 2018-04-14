import { union } from 'lodash';

Vue.component('show-board', {
	mixins: [require('../mixins/determines-scroll-position')],
	props: ['board'],

	data() {
		return {
			creatingNewThread: false,
			threads: [],
			currentPage: 1,
			lastPage: 1
		}
	},

	mounted () {
		this.listenForBroadcasts();
		this.fetchThreads();
	},


	watch: {
		atBottom: function (value) {
			if (value) {
				this.fetchMoreThreads();
			}
		}
	},

	computed: {
		atLastPage () {
			return this.currentPage >= this.lastPage;
		}
	},

	methods: {
		/**
		 * Redirect to a given thread.
		 * 
		 * @param  Object thread 
		 */
		redirectToThread (thread) {
			window.location.replace(
				thread.link
			);
		},

		/**
		 * Increment the page and fetch the next segment
		 */
		fetchMoreThreads () {
			if (! this.atLastPage) {
				this.currentPage += this.currentPage;
				this.fetchThreads();
			}
		},

		/**
		 * Fetch the threads at the current page.
		 */
		fetchThreads () {
			axios.get(`/api/boards/${this.board.slug}/threads`, {
				params: {
					page: this.currentPage
				}
			})
			.then((response) => {
				this.lastPage = response.data.last_page;
				this.threads = union(this.threads, response.data.data);
			})
			.catch((errors) => console.log(errors))
		},

		/**
		 * Listen for new threads events being broadcasting by server
		 */
		listenForBroadcasts () {
			Echo.channel(`App.Board.${this.board.id}`)
				.listen('Thread.ThreadCreated', (event) => {
					this.threads.unshift(event.thread);
				})
				.listen(`Thread.ThreadUpdated`, (event) => {
					const index = this.threads.findIndex(thread => thread.id === event.thread.id);

					if (index !== -1) {
						Vue.set(this.threads, index, event.thread);
					}
				})
				.listen('Thread.ThreadDeleted', (event) => {
					this.threads = this.threads.filter(thread => thread.id !== event.thread.id);
				})						
		}
	}
})