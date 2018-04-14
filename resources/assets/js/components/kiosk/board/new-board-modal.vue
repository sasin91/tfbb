<template>
	<div id="new-board-modal" class="modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Create new board</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <form @submit.prevent>
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
	        <button type="button" class="btn btn-primary" @click="doStore">Create!</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="hide">Close</button>
	      </div>
	    </div>
	  </div>
	</div>
</template>

<script>
	export default {
		data () {
			return {
				boardForm: new SparkForm({
					name: '',
					description: '',
				})
			}
		},

		methods: {
			show () {
				$('#new-board-modal').modal('show');
			},

			hide () {
				$('#new-board-modal').modal('hide');
			},

			doStore () {
				Spark.post('/api/boards', this.boardForm)
					.then(() => {
						this.hide()
						swal('Done', 'The board has been created!', 'success')
					})
			}
		}
	}
</script>