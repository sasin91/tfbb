import ManageBoardModal from './manage-board-modal.vue';
import NewBoardModal from './new-board-modal.vue';

Vue.component('kiosk-manage-boards', {
	components: {ManageBoardModal, NewBoardModal},

	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'boards' && self.boards.length === 0) {
            	self.fetchBoards();
            	self.listenForNewBoards();
            }

            return true;
        });
	},

	data () {
		return {
			boards: [],

			searchForm: new SparkForm({
				query: ''
			})
		}
	},

	methods: {
		fetchBoards (query = {}) {
			axios.get('/api/boards', {
				params: query,
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.boards = data;
				this.listenForEvents();
			});
		},

		showBoard (board) {
			this.$refs.manageBoardModal.show(board);
		},

		showNewBoardModal () {
			this.$refs.newBoardModal.show();
		},

		listenForNewBoards () {
			Echo.channel('App.Board')
				.listen('Board.BoardCreated', (event) => {
					if (! this.boards.includes(event.board)) {
						this.boards.unshift(event.board);
					}
				})
		},

		listenForEvents () {
			const self = this;

			this.boards.forEach(function (board) {
				Echo.channel(`App.Board.${board.id}`)
					.listen('Board.BoardUpdated', (event) => {
						const index = self.boards.findIndex(board => parseInt(board.id) === parseInt(event.board.id))
						
						self.$set(self.boards, index, event.board);
					})
					.listen('Board.BoardDeleted', (event) => {
						self.boards = self.boards.filter(board => parseInt(board.id) !== parseInt(event.board.id));
					})
			});
		}
	}
})