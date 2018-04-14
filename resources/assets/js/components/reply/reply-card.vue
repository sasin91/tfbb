<template>
	<div class="card mt-2">
		<div v-if="! editing">			
			<div class="card-header" v-if="reply.title">
				<h1 class="card-title">{{ reply.title }}</h1>
			</div>
			<div class="card-body">
				<p class="card-text lead">
					{{ reply.body }}
				</p>
			</div>
		</div>

		<div v-if="isEditable" class="ml-2 mb-2 editable">
	  		<form @submit.prevent v-show="editing">
	  			<div class="form-group mt-2">
	  				<input type="text" name="title" class="form-control" v-model="editReplyForm.title"></input>
				    <span class="invalid-feedback" v-show="editReplyForm.errors.has('title')">
				        {{ editReplyForm.errors.get('title') }}
				    </span>
	  			</div>

				<div class="form-group mt-2">
					<textarea class="form-control" name="body" v-model="editReplyForm.body"></textarea>
				    <span class="invalid-feedback" v-show="editReplyForm.errors.has('body')">
				        {{ editReplyForm.errors.get('body') }}
				    </span>
				</div>
	  		</form>

	  		<button @click="editing = !editing" class="btn btn-primary">Edit</button>
	  		<button @click="deleteReply" class="btn btn-warning">Delete</button>
	  		
	  		<span v-show="editing" role="group">
		  		<button @click="updateReply" class="btn btn-success">Save changes</button>
		  		<button @click="setInitialState" class="btn btn-primary">Cancel</button>
	  		</span>
		</div>

		<div class="card-footer">
	    	<h6 class="card-subtitle">
		  	By
		  	<a v-if="reply.creator.profile" :href="reply.creator.profile.link">
		  		{{ reply.creator.name }}
		  	</a>
		  	<template v-else>
		  		{{ reply.creator.name }}
		  	</template>
		  	<small class="text-muted">
				{{ reply.created_at | datetime }}
			</small>
			</h6>
		</div>
	</div>
</template>

<script>
	export default {
		props: { reply:Object },

		data () {
			return {
				editReplyForm: new SparkForm({
					title: '',
					body: ''
				}),

				editing: false
			}
		},

		computed: {
			isEditable () {
				return this.reply.creator.id === Spark.state.user.id;
			}
		},

		mounted () {
			this.setInitialState();
		},

		methods: {
			updateReply () {
				Spark.patch(`/api/replies/${this.reply.hashid}`, this.editReplyForm)
					 .then((reply) => {
					 	this.setInitialState();
					 	this.$emit('updated', reply);
					 })
			},

			deleteReply () {
				axios.delete(`/api/replies/${this.reply.hashid}`)
					 .then(() => swal('Done.', 'Your reply has been scrapped.', 'success'))
					 .catch((errors) => swal('Uh...', 'Something went wrong, and your reply lives on.', 'error'))
			},

			setInitialState () {
				if (this.isEditable) {
					this.editReplyForm.title = this.reply.title;
					this.editReplyForm.body = this.reply.body;

					this.editing = false;
				}
			}
		}
	}
</script>