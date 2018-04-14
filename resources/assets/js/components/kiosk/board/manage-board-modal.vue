<template>
	<div id="manage-board-modal" class="modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">{{ board.name }}</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form>
				<div class="form-group row">
				    <label class="col-md-4 control-label">Name</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="name"
				               v-model="boardForm.name"
				               :class="{'is-invalid': boardForm.errors.has('name')}">

				        <span class="invalid-feedback" v-show="boardForm.errors.has('name')">
				            @{{ boardForm.errors.get('name') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">Description</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="description"
				               v-model="boardForm.description"
				               :class="{'is-invalid': boardForm.errors.has('description')}">

				        <span class="invalid-feedback" v-show="boardForm.errors.has('description')">
				            @{{ boardForm.errors.get('description') }}
				        </span>
				    </div>
				</div>
			</form>
	      </div>
	      <div class="modal-footer">
	        <button v-if="hasChanges" type="button" class="btn btn-primary" @click="doUpdate">Save changes</button>

	        <button v-if="board.published" type="button" class="btn btn-info" @click="draft">Draft</button>
	        <button v-else type="button" class="btn btn-success" @click="publish">Publish</button>

	        <button type="button" class="btn btn-danger" @click="doDestroy">Delete</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="hide">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<script>
	import { filter } from 'lodash/filter'

	export default {
		data () {
			return {
				board: {},

				boardForm: new SparkForm({
					name: '',
					description: '',
				})
			}
		},

		computed: {
			hasChanges () {
				return this.changes.length > 0;
			},

			changes () {
				const keys = ['name', 'description'];

				return keys.filter((key) => this.board[key] !== this.boardForm[key]);
			}
		},

		methods: {
			show (board) {
				this.board = board;

				this.boardForm.name = board.name;
				this.boardForm.description = board.description;

				$('#manage-board-modal').modal('show');
			},

			hide () {
				this.board = {};

				this.boardForm.name = '';
				this.boardForm.description = '';

				$('#manage-board-modal').modal('hide');
			},

			draft () {
				axios.post(`/api/boards/${this.board.slug}/draft`)
					.then(() => {
						this.hide()
						swal('Drafted', 'Board has been marked as draft.', 'success')
					})
			},

			publish () {
				axios.post(`/api/boards/${this.board.slug}/publish`)
					.then(() => {
						this.hide()
						swal('Published', 'Board has been published and should be visible to users.', 'success')
					})
			},

			doUpdate () {
				Spark.patch(`/api/boards/${this.board.slug}`, this.boardForm)
					.then(() => {
						this.hide()
						swal('Updated', 'Board updated.', 'success')
					})
			},

			doDestroy () {
				axios.delete(`/api/boards/${this.board.slug}`)
					 .then(() => {
					 	this.hide();
					 	swal('Deleted', 'Board deleted.', 'success')
					 })
			}
		}
	}
</script>