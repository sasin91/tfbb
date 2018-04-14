import PhotoPreview from '../photo-preview.vue';
import Photo from '../photo.js';

Vue.component('new-thread', {
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
})
