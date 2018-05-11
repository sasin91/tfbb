<template>
	<div id="manage-workout-modal" class="modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog modal-dialog--wide" role="document">
	    <div class="modal-content">
	      <div class="modal-body">
	      	<spinner :spin="uploading">
	      		{{ uploadStatusMessage }}
	      	</spinner>	
	      	<div v-if="hasMedia" class="media container">
		      	<div class="row" v-if="uploadedPhotos.length > 0">
			  		<photo-preview v-for="(photo, index) in uploadedPhotos" :key="index" :media="photo.url"></photo-preview>
	      		</div> 

	      		<div class="row" v-if="uploadedVideos.length > 0">
	      			<video-player v-for="(video, index) in uploadedVideos" :key="index" :media="video.url"></video-player>
	      		</div>   

	      		<div class="row" v-if="uploadDocuments.length > 0">
					<ul class="list-group list-group-flush">
					  <li v-for="(document, index) in uploadedDocuments" :key="index" class="list-group-item">
					  	<a target="_blank" :href="document.url">{{ document.name }}</a>
					  </li>
					</ul>
	      		</div> 		
	      	</div>
	      	
	      	<hr v-show="hasMedia" class="divider"></hr>

	        <form>
				<div class="form-group row">
				    <label class="col-md-4 control-label">Title</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="title"
				               v-model="workoutForm.title"
				               :class="{'is-invalid': workoutForm.errors.has('title')}">

				        <span class="invalid-feedback" v-show="workoutForm.errors.has('title')">
				            {{ workoutForm.errors.get('title') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">Level</label>

				    <div class="col-md-6">
						<select 
							v-model="workoutForm.level" 
							name="level" 
							class="custom-select form-control" 
							:class="{'is-invalid': workoutForm.errors.has('level')}"
						> 
						  <option v-for="level in config.levels" :value="level">{{ level | capitalize }}</option>
						</select>
				       
				        <span class="invalid-feedback" v-show="workoutForm.errors.has('level')">
				            {{ workoutForm.errors.get('level') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">Type</label>

				    <div class="col-md-6">
						<select 
							v-model="workoutForm.type" 
							name="type" 
							class="custom-select form-control" 
							:class="{'is-invalid': workoutForm.errors.has('type')}"
						> 
						  <option v-for="style in config.styles" :value="style">{{ style | capitalize }}</option>
						</select>
	
				        <span class="invalid-feedback" v-show="workoutForm.errors.has('type')">
				            {{ workoutForm.errors.get('type') }}
				        </span>
				    </div>
				</div>	

				<div class="form-group row">
				    <label class="col-md-4 control-label">Summary</label>

				    <div class="col-md-6">
				        <input  type="text" class="form-control" name="summary"
				                v-model="workoutForm.summary"
				                :class="{'is-invalid': workoutForm.errors.has('summary')}">

				        <span class="invalid-feedback" v-show="workoutForm.errors.has('summary')">
				            {{ workoutForm.errors.get('summary') }}
				        </span>
				    </div>
				</div>	

				<div class="form-group row">
				    <label class="col-md-4 control-label">Body</label>

				    <div class="col-md-6">
						<quill-editor
							v-model="workoutForm.body"
							ref="editor"
							:options="editorOptions"
						></quill-editor>
					    <span class="invalid-feedback" v-show="workoutForm.errors.has('body')">
					        {{ workoutForm.errors.get('body') }}
					    </span>
				    </div>
				</div>
			</form>
	      </div>
	      <div class="modal-footer border-top-0">
			<div class="input-group">
			  <div class="custom-file">
			  	<input 
					multiple
					id="inputDocuments"
					class="custom-file-input" 
					type="file" 
					accept="application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" 
					ref="fileInput" 
					@change="uploadDocuments"
				></input>	
			    <label class="custom-file-label" for="inputDocuments">Choose document(s)</label>
			  </div>
			</div>

	      	<button type="button" class="btn btn-link">
	        	<a :href="workout.link" target="_blank">View workout</a>
	      	</button>

	      	<button type="button" class="btn btn-primary" @click="selectAsWOTM">Select as WOTM</button>

	        <button v-if="hasChanges" type="button" class="btn btn-primary" @click="doUpdate">Save changes</button>

	        <button type="button" class="btn btn-danger" @click="doDestroy">Delete</button>
	        <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="hide">Close</button>

			<input 
				multiple
				v-show="false" 
				type="file" 
				accept="image/*" 
				class="hidden" 
				ref="imageInput" 
				@change="uploadPhotos"
			></input>

			<input 
				multiple 
				v-show="false" 
				type="file" 
				accept="video/*" 
				class="hidden" 
				ref="videoInput" 
				@change="uploadVideos"
			></input>	
	      </div>
	    </div>
	  </div>
	</div>
</template>

<script>
	import { orderBy, forEach, union } from 'lodash';
	import { formDataArray } from '../../helpers';
	import Photo from '../../photo';
	import Spinner from '../../spinner.vue';
	import PhotoPreview from '../../photo-preview.vue';
	import VideoPlayer from '../../video-player.vue';

	export default {
		components: { Spinner, PhotoPreview, VideoPlayer },

		props: ['config'],

		data () {
			return {
				workout: {},

				editorOptions: { 
					modules: { 
						markdownShortcuts:{},
						toolbar: [
							[{
								header: [1,2,3,4,5]
							}],
							[
								'blockquote', 'code-block', 
								'bold', 'italic', 'underline', 'strike',
							],
							[{ 'list': 'ordered'}, { 'list': 'bullet' }],	
							['image', 'video', 'link']						
						]
					} 
				},

				uploading: false,
				uploadStatusMessage: '',
				uploadedPhotos: [],
				uploadedVideos: [],
				uploadedDocuments: [],

				workoutForm: new SparkForm({
					title: '',
					level: '',
					type: '',
					summary: '',
					body: ''
				})
			}
		},

		mounted () {
			const quillToolbar = this.$refs.editor.quill.getModule('toolbar');
			quillToolbar.addHandler('image', () => this.$refs.imageInput.click())
			quillToolbar.addHandler('video', () => this.$refs.videoInput.click())
		},

		computed: {
			hasChanges () {
				return this.changes.length > 0;
			},

			hasMedia () {
				return this.uploadedPhotos.length > 0
					|| this.uploadedVideos.length > 0
					|| this.uploadedDocuments.length > 0;
			},

			changes () {
				const keys = ['title', 'level', 'type', 'summary', 'body'];

				return keys.filter((key) => this.workout[key] !== this.workoutForm[key]);
			}
		},

		methods: {
			async uploadPhotos (inputEvent) {
				this.uploading = true;
				this.uploadStatusMessage = 'Processing photos...';
				let formData = new FormData;

				for (var i = 0; i < inputEvent.target.files.length; i++) {
					const result = await new Photo(inputEvent.target.files[i]).prepareForUpload();

					formData.append(`photos[${i}]`, result.photo);
				}

				this.uploadStatusMessage = 'Uploading photos...';
				axios.post(`/api/workouts/${this.workout.slug}/photos`, formData)
					.then(({ data }) => this.uploadedPhotos.push(data))
					.catch((errors) => swal('Server rejected the photo(s).', 'This is usually due to unsupported format or dimensions.', 'error'))
					.finally(() => {
						this.uploadStatusMessage = '';
						this.uploading = false;
					})
			},

			uploadVideos (inputEvent) {
				this.uploading = true;
				this.uploadStatusMessage = 'Uploading videos...';

				axios.post(`/api/workouts/${this.workout.slug}/videos`, formDataArray(inputEvent, 'videos'))
					.then(({ data }) => this.uploadedVideos.push(data))
					.catch((errors) => swal('Server rejected the video(s)', 'This is probably due to unsupported format.', 'error'))
					.finally(() => {
						this.uploading = false;
						this.uploadStatusMessage = '';
					})
			},

			uploadDocuments (inputEvent) {
				this.uploading = true;
				this.uploadStatusMessage = 'Uploading documents...';

				axios.post(`/api/workouts/${this.workout.slug}/documents`, formDataArray(inputEvent, 'documents'))
					.then(({ data }) => this.uploadedDocuments.push(data))
					.catch((errors) => swal('Server rejected the document(s)', 'This is probably due to unsupported format.', 'error'))
					.finally(() => {
						this.uploading = false;
						this.uploadStatusMessage = '';
					})
			},

			inputToFormData (inputEvent, key = 'file') {
				let formData = new FormData;

				for (var i = 0; i < inputEvent.target.files.length; i++) {
					const file = inputEvent.target.files[i];

					formData.append(`${key}[${i}]`, file);
				}

				return formData;
			},

			async dispatchQueuedFileUploads () {
				[photos, videos, documents] = await Promise.all(orderBy(this.uploadQueue, (item) => item.category));

				console.log(photos, videos, documents);
			},

			show (workout) {
				this.workout = workout;

				this.workoutForm.title = workout.title;
				this.workoutForm.level = workout.level;
				this.workoutForm.type = workout.type;
				this.workoutForm.summary = workout.summary;
				this.workoutForm.body = workout.body;

				$('#manage-workout-modal').modal('show');
			},

			hide () {
				this.workout = {};

				this.workoutForm.title ='';
				this.workoutForm.level = '';
				this.workoutForm.type = '';
				this.workoutForm.summary = '';
				this.workoutForm.body = '';

				$('#manage-workout-modal').modal('hide');
			},

			selectAsWOTM () {
				axios.post(`/api/workouts/${this.workout.slug}/select-as-wotm`)
					 .then(() => swal('Alrighty', 'The workout was selected for the current month.', 'success'))
					 .catch(({ data }) => {
					 	console.error(data);

					 	swal('Uh...', data.errors.join(), 'error');
					 });
			},

			doUpdate () {
				Spark.patch(`/api/workouts/${this.workout.slug}`, this.workoutForm)
					.then(() => {
						this.hide()
						swal('Updated', 'Workout updated.', 'success')
					})
			},

			doDestroy () {
				axios.delete(`/api/workouts/${this.workout.slug}`)
					 .then(() => {
					 	this.hide();
					 	swal('Deleted', 'Workout deleted.', 'success')
					 })
			}
		}
	}
</script>