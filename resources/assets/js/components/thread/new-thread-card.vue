<template>
				<div class="card">
					<div class="card-header">
						<h4 class="card-title">
							Create new thread
						</h4>
					</div>
					<div class="card-block">

					  <div class="pl-2">
					  	<photo-preview  
					  		v-for="photo in queuedPhotoUploads" 
					  		:key="photo.id"
					  		:photo="photo.preview"
					  	></photo-preview>
					  </div>

				        <form @submit.prevent class="mt-2">
							<div class="form-group row">
							    <label class="col-md-4 control-label">Title</label>

							    <div class="col-md-6">
							        <input type="text" class="form-control" name="title"
							               v-model="newThreadForm.title"
							               :class="{'is-invalid': newThreadForm.errors.has('title')}">

							        <span class="invalid-feedback" v-show="newThreadForm.errors.has('title')">
							            {{ newThreadForm.errors.get('title') }}
							        </span>
							    </div>
							</div>

							<div class="form-group row">
							    <label class="col-md-4 control-label">Body</label>

							    <div class="col-md-6">
									<quill-editor
										v-model="newThreadForm.body"
										ref="editor"
										:id="editorId"
										:options="editorOptions"
									></quill-editor>

									<input 
										v-show="false" 
										type="file" 
										class="hidden" 
										ref="fileInput" 
										@change="queuePhotoUpload"
									></input>

							        <span class="invalid-feedback" v-show="newThreadForm.errors.has('body')">
							           	{{ newThreadForm.errors.get('body') }}
							       	</span>
							    </div>
							</div>

							<div class="form-group row ml-2">
								<button @click="store" class="btn btn-primary" type="submit">Create!</button>
							</div>
						</form>
					</div>
				</div>
</template>

<script>
	import PhotoPreview from '../photo-preview.vue';
	import Photo from '../photo.js';

	export default {
	components: { PhotoPreview },

	props: {
		board: { type: Object },
	},
	data () {
		return {
			newThreadForm: new SparkForm({
				body: '',
				title: ''
			}),

			editorOptions: { 
				modules: { 
					markdownShortcuts:{},
					toolbar: [
						[{
							header: [1,2,3,4,5],
						}],
						['blockquote', 'image']					
					]
				} 
			},

			queuedPhotosCount: 0,
			queuedPhotoUploads: [],

			failedPhotoUploads: []
		}
	},

	mounted () {
		const quillToolbar = this.$refs.editor.quill.getModule('toolbar');
		quillToolbar.addHandler('image', () => this.$refs.fileInput.click())
	},

	computed: {
		editorId () {
			return `editor-${this.board.id}`
		}
	},

	methods: {
		store () {
			Spark.post(`/api/boards/${this.board.slug}/threads`, this.newThreadForm)
				 .then((thread) => {
				 	if (this.queuedPhotoUploads.length > 0) {
				 		this.uploadQueuedPhotos(thread);
				 	}

				 	this.$emit('created', thread);

				 	this.blank();
				 });
		},

		blank() {
			this.newThreadForm.title = '';
			this.newThreadForm.body = '';

			this.queuedPhotoUploads = [];
			this.queuedPhotosCount = 0;

			this.failedPhotoUploads = [];
		},

		queuePhotoUpload(event) {
			for (var i = 0; i < event.target.files.length; i++) {
				const file = event.target.files[i];

				new Photo(file).prepareForUpload().then(result => {
					this.queuedPhotoUploads.push({
						id: this.queuedPhotosCount++,
						file: result.photo,
						preview: result.preview,
						original: file
					})
				})
			}
		},

		uploadQueuedPhotos (thread) {
			this.queuedPhotoUploads.forEach(upload => {
				const formData = new FormData;
				formData.append('photo', upload.file);

			    axios.post(`/api/threads/${thread.slug}/photos`, formData, { headers: { 'content-type': 'multipart/form-data'} })
			        .then(({ data }) =>  this.$emit('uploaded', data))
				    .catch(({ response }) => {
				    	this.failedPhotoUploads.push({
				    		name: upload.file.name,
				    		reason: response.data.errors.photo.join(', ')
				    	});
				    });
				});

				if (this.failedPhotoUploads.length > 0) {
				    swal("Your thread was created, but some photos failed.", '', 'error')
				    console.log(this.failedPhotoUploads)
				}
			},
		}
	}
</script>