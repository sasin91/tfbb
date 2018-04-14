Vue.component('thread-participants', {
	props: { threadId: Number },

	data () {
		return {
			participants: []
		}
	},

	mounted () {
		Echo.join(`App.Thread.${this.threadId}`)
			.here((users) => this.participants = users)
			.joining((user) => this.participants.push(user))
			.leaving((user) => this.participants = this.participants.filter(participant => participant.id !== user.id))
	}
})