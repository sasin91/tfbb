<template>
	<div id="new-workout-modal" class="modal" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title">Create new workout</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	      	<input 
	      		type="file" 
	      		name="file_upload" 
	      		class="btn btn-link" 
	      		accept="image/*,video/*" 
	      		multiple 
	      		@change="uploadQueue.add($event.target.files)"
	      	>

	        <form @submit.prevent>
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
						  <option value="Beginner" selected>Beginner</option>
						  <option value="Intermediate">Intermediate</option>
						  <option value="Advanced">Advanced</option>
						  <option value="Elite">Elite</option>
						</select>
				       
				        <span class="invalid-feedback" v-show="workoutForm.errors.has('level')">
				            {{ workoutForm.errors.get('level') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">Type</label>

				    <div class="col-md-6">
				        <input  type="text" class="form-control" name="type"
				                v-model="workoutForm.type"
				                :class="{'is-invalid': workoutForm.errors.has('type')}">

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
				    	<textarea 
				    		class="form-control"
				    		:class="{'is-invalid': workoutForm.errors.has('body')}" 
				    		name="body" 
				    		v-model="workoutForm.body"
				    	></textarea>

				        <span class="invalid-feedback" v-show="workoutForm.errors.has('body')">
				            {{ workoutForm.errors.get('body') }}
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
	import Photo from '../../photo'
	import FileUploadQueue from '../../file-upload-queue'

	import PhotoPreview from '../../photo-preview.vue'
	import VideoPlayer from '../../video-player.vue'

	export default {
		components: { PhotoPreview, VideoPlayer },

		data () {
			return {
				workout: {},

				workoutForm: new SparkForm({
					title: '',
					type: 'BodyBuilding',
					level: 'Beginner',
					summary: '',
					body: ''
				}),

				uploadQueue: new FileUploadQueue([
					{ name: 'photos', mime: 'image', handler: this.uploadPhotos },
					{ name: 'videos', mime: 'video', handler: this.uploadVideos }
				])
			}
		},

		methods: {
			show () {
				$('#new-workout-modal').modal('show');
			},

			hide () {
				$('#new-workout-modal').modal('hide');
			},

			doStore () {
				Spark.post('/api/workouts', this.workoutForm)
					.then((workout) => {
						this.workout = workout;
						this.uploadQueue.process();

						this.hide()
						swal('Done', 'The workout has been created!', 'success')
					})
			},

			async uploadPhotos (photos) {
				const formData = new FormData();

				for (var i = 0; i < photos.length; i++) {
					let result = await new Photo(photos[i]).prepareForUpload();

					formData.append(`photos[${i}]`, result.photo);
					//this.uploadedPhotos.push(result.preview);
				}

				const { data } = await axios.post(`/api/workouts/${this.workout.slug}/photos`, formData);

				return data;
			},

			async uploadVideos (videos) {
				const formData = new FormData();

				for (var i = 0; i < videos.length; i++) {
					formData.append(`videos[${i}]`, videos[i]);
				}

				const { data } = await axios.post(`/api/workouts/${this.workout.slug}/videos`, formData);
				// this.uploadedVideos.push(data);
				
				return data;
			},
		}
	}
</script>