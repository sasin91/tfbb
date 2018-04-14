import { union } from 'lodash';

Vue.component('thread-replies', {
	mixins: [require('../../mixins/determines-scroll-position')],
	props: { thread: Object},

	data () {
		return {
			currentPage: 1,
			lastPage: null,

			replies: [],

			creatingNewReply: false,

			newReplyForm: new SparkForm({
				title: '',
				body: ''
			})
		}
	},

	mounted () {
		this.fetchReplies();
		this.listenForBroadcasts();
	},

	watch: {
		atBottom: function (value) {
			if (value) {
				this.fetchMoreReplies();
			}
		}
	},

	computed: {
		atLastPage () {
			return this.currentPage >= this.lastPage;
		}
	},

	methods: {
		listenForBroadcasts () {
			Echo.channel(`App.Thread.${this.thread.id}`)
				.listen('Reply.ReplyCreated', (event) => {
					this.replies.unshift(event.reply);
				})
				.listen(`Reply.ReplyUpdated`, (event) => {
					const index = this.replies.findIndex(reply => reply.id === event.reply.id);

					if (index !== -1) {
						Vue.set(this.replies, index, event.reply);
					}
				})
				.listen('Reply.ReplyDeleted', (event) => {
					this.replies = this.replies.filter(reply => reply.id !== event.reply.id);
				})						
		},

		fetchMoreReplies () {
			if (! this.atLastPage) {
				this.currentPage += this.currentPage;
				this.fetchReplies();
			}
		},

		fetchReplies () {
			axios.get(`/api/threads/${this.thread.hashid}/replies`, {
				params: {
					page: this.currentPage
				}
			})
			.then((response) => {
				this.lastPage = response.data.last_page;
				this.replies = union(this.replies, response.data.data);
			})
			.catch((errors) => console.log(errors))
		},

		storeNewReply () {
			Spark.post(`/api/threads/${this.thread.hashid}/replies`, this.newReplyForm)
				 .then((reply) => {
				 	reply.creator = Spark.state.user;
				 	reply.creator.profile = Spark.state.user.profile;

				 	this.creatingNewReply = false;
				 	this.replies.unshift(reply);
				 })
		}
	}
})