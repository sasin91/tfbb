module.exports = {
	data() {
		return {
			atBottom: false,
		}
	},

	created () {
		window.addEventListener('scroll', () => {
			this.atBottom = this.isAtBottom();
		})
	},

	methods: {
		/**
		 * Determine if we're bumping against the buttom of the page.
		 *
		 * @credits https://scotch.io/tutorials/simple-asynchronous-infinite-scroll-with-vue-watchers
		 * @return Boolean
		 */
		isAtBottom () {
	      const scrollY = window.scrollY
	      const visible = document.documentElement.clientHeight
	      const pageHeight = document.documentElement.scrollHeight
	      const bottomOfPage = visible + scrollY >= pageHeight

	      return bottomOfPage || pageHeight < visible
		},
	}
}