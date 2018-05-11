<template>
	<div class="card">
		<div class="card-header text-center">
			<p class="text-danger" v-if="thread.locked_at">
				<i class="fa fa-lock"></i>
				{{ thread.locked_because ? thread.locked_because : 'the thread is locked.' }}
			</p>

		  <h3 class="card-title">
		  	<a :href="thread.link">
		  		{{ thread.title }}
		  	</a>
		  </h3>
		</div>
		<div class="card-body">
			<spinner :spin="uploading"></spinner>
			<template v-if="images.length > 0">
			  <div class="row">
			  	<photo-preview v-for="photo in images" :key="photo.url" :media="photo"></photo-preview>
			  </div>
			  <hr class="divider"></hr>		
			</template>

		  	<span v-if="isEditable" class="editable">
		  		<button @click="editing = !editing" class="btn btn-primary">Show editor</button>
		  		<button @click="deleteThread" class="btn btn-warning">Delete</button>
		  		
		  		<span v-show="editing" role="group">
			  		<button @click="updateThread" class="btn btn-success">Save changes</button>
			  		<button @click="cancelChanges" class="btn btn-primary">Cancel</button>
		  		</span>

		  		<span role="group">
				    <button 
				    	v-if="thread.locked_at" 
				    	@click="unlockThread"
				    	type="button" 
				    	class="btn btn-success"
				    >Unlock thread</button>	

				    <button 
				    	v-else
				    	@click="lockThread" 
				    	type="button" 
				    	class="btn btn-warning"
				    >Lock thread</button>
		  		</span>

		  		<hr class="divider" />

		  		<form @submit.prevent>		
					<div v-show="editing" class="form-group mt-2">
						<quill-editor
							v-model="editThreadForm.body"
							ref="editor"
							:options="editorOptions"
						></quill-editor>
						<input 
							v-show="false" 
							type="file" 
							class="hidden" 
							ref="fileInput" 
							@change="startUploadingPhotoFromInput"
						></input>
					    <span class="invalid-feedback" v-show="editThreadForm.errors.has('body')">
					        {{ editThreadForm.errors.get('body') }}
					    </span>
					</div>
		  		</form>
			  <span v-if="editing === false" v-html="editThreadForm.body" class="card-text"></span>
		  	</span>

			<template v-else>
			  <span v-if="preview" class="card-text">
			  	{{ thread.summary }}
			  </span>

			  <span v-else v-html="thread.body" class="card-text"></span>
			</template>	  
		</div>

		<div class="card-footer">
		  	<h6 class="card-subtitle">
			  	By
			  	<a v-if="thread.creator.profile" :href="thread.creator.profile.link">
			  		{{ thread.creator.name }}
			  	</a>
			  	<template v-else>
			  		{{ thread.creator.name }}
			  	</template>
			  	<small class="text-muted">
					{{ thread.created_at | datetime }}
				</small>
				<span class="pull-right">
					<span 
						v-show="thread.replies_count > 0" 
						class="badge badge-pill badge-info"
					>{{ thread.replies_count }} replies</span>

					<span 
						class="badge badge-pill badge-light" 
						data-toggle="tooltip" 
						data-placement="top" 
						:title="participantsList"
					>{{ participants.length }} is viewing this thread</span>				
				</span>
			</h6>
		</div>
	</div>
</template>

<script>
	import Photo from '../photo'
	import Spinner from '../spinner.vue'
	import PhotoPreview from '../photo-preview.vue'

	export default {
		components: { Spinner, PhotoPreview },

		props: { 
			thread: Object, 
			photos: { type: Array, default: () => [] },
			supportsPhotoUpload: { type: Boolean, default: false },
			preview: { type: Boolean, default: true }
		},

		data () {
			return {
				recentlyEdited: false,
				editing: false,
				uploading: false,

				editorOptions: { 
					modules: { 
						markdownShortcuts:{},
						toolbar: [
							[{
								header: [1,2,3,4,5]
							}]						
						]
					} 
				},

				editThreadForm: new SparkForm({
					body: ''
				}),

				images: [...this.photos],

				participants: []
			}
		},

		created () {
			if (this.supportsPhotoUpload) {
				this.editorOptions.modules.toolbar.push(['blockquote', 'image'])
			} else {
				this.editorOptions.modules.toolbar.push(['blockquote'])
			}
		},

		mounted () {
			if (this.isEditable) {
				this.editThreadForm.body = this.thread.body;

				const quillToolbar = this.$refs.editor.quill.getModule('toolbar');
				quillToolbar.addHandler('image', () => this.$refs.fileInput.click())
			}

			Echo.join(`App.Thread.${this.threadId}`)
				.here((users) => this.participants = users)
				.joining((user) => this.participants.push(user))
				.leaving((user) => this.participants = this.participants.filter(participant => participant.id !== user.id))
		},

		watch: {
			'thread.body': function (value) {
				if (this.isUnchanged) {
					this.editThreadForm.body = value;
				} else if(this.recentlyEdited === false) {
					swal({
					  title: "Woop",
					  text: "The thread you're currently editing, has changed.",
					  type: "info",
					  showCancelButton: false,
					  confirmButtonText: "refresh.",
					  closeOnConfirm: true,
					}, function(){
					  window.location.reload();
					});
				}
			}
		},

		computed: {
			participantsList () {
				return this.participants.map(p => p.name).join(', ');
			},

			isEditable () {
				return this.currentUser.is_moderator 
					|| this.thread.creator.id === this.currentUser.id;
			},

			editorId () {
				return `thread-editor-${this.thread.id}`;
			},

			isUnchanged () {
				return this.editThreadForm.body !== this.thread.body;
			},

			currentUser () {
				return Spark.state.user;
			}
		},

		methods: {
			lockThread () {
				axios.post(`/api/threads/${this.thread.hashid}/lock`);
			},

			unlockThread () {
				axios.post(`/api/threads/${this.thread.hashid}/unlock`);
			},

			clickFileInput () {
				this.$refs.fileInput.click();
			},

			startUploadingPhotoFromInput (event) {
				const file = event.target.files[0];

				new Photo(file).prepareForUpload().then(result => this.uploadPhoto(result.photo, result.preview))
			},

			uploadPhoto (photo, preview = null) {
				this.uploading = true

		        const formData = new FormData()
		        formData.append('photo', photo)

		        axios.post(`/api/threads/${this.thread.slug}/photos`, formData)
		        	.then(({ data }) => {
		        		this.images.push({
		        			url: data.url,
		        			thumbnail: data.thumbnail ? data.thumbnail : preview
		        		})

		        		this.$refs.fileUpload.$el.value = ''
		        	})
				    .catch(({ response }) => swal('Uh oh!', response.data.errors.photo.join(', '), 'error'))
				    .finally(() => this.uploading = false)
			},

			cancelChanges () {
				this.editing = false;
				this.body = this.thread.body;
			},

			updateThread () {
				Spark.patch(`/api/threads/${this.thread.slug}`, this.editThreadForm)
					 .then(() => {
					 	this.editing = false
					 	this.recentlyEdited = true;
					 	swal('Updated.', 'Your thread has been updated.', 'success')
					 })
			},

			deleteThread () {
				Spark.delete(`/api/threads/${this.thread.slug}`, this.editThreadForm)
					 .then(() => swal('Done.', 'Your thread has been deleted.', 'success'))
			}
		}
	}
</script>