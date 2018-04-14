import Photo from '../photo'
import PhotoPreview from '../photo-preview.vue'
import VideoPlayer from '../video-player.vue'

Vue.component('profile-details', {
	components: { PhotoPreview, VideoPlayer },

	props: {
		user:Object,
	},

	data () {
		return {
			profile: null,


			uploadQueue: [],
			uploadedPhotos: [],
			uploadedVideos: [],

			form: new SparkForm({
		    	story: '',
		    	goals: '',
		        training_level: '',
		        training_style: ''
			}),

			editorOptions: { 
				modules: { 
					markdownShortcuts:{},
					toolbar: [
						[{ header: [1,2,3,4,5] }],
						['bold', 'italic', 'underline', 'strike'],
						[{ 'list': 'ordered'}, { 'list': 'bullet' }],
						['blockquote', 'code-block'],				
					]
				} 
			},
		}
	},

	mounted () {
		this.fetchProfile();
	},

	watch: {
		profile (exists) {
			if (exists) {
				this.processQueuedUploads();
			}
		}
	},

	methods: {
		async fetchProfile () {
			const {data} = await axios.get('/profile', { accept: 'application/json' });

			if (data) {
				this.profile = data;

				this.form.story = data.story;
				this.form.goals = data.goals;
				this.training_style = data.training_style;
				this.training_level = data.training_level;

				Echo.private(`App.User.${data.creator_id}`)
					.listen('Profile.ProfileUpdated', (event) => {
						this.profile = event.profile;
					})
					.listen('Profile.ProfileDeleted', (event) => {
						this.profile = null;
					});
			}
		},

		queueFilesForUpload (event) {
			this.uploadQueue = event.target.files;
		},

		processQueuedUploads () {
			const videos = [];
			const photos = [];

			while (this.uploadQueue.length > 0) {
				let file = this.uploadQueue.pop();

				if ((/image/g).test(file.type)) {
					photos.push(file);
				} else if ((/video/g).test(file.type)) {
					videos.push(file);
				}
			}

			if (photos.length > 0) {
				this.uploadPhotos(photos);
			}

			if (videos.length > 0) {
				this.uploadVideos(videos);
			}
		},

		async uploadPhotos (photos) {
			const formData = new FormData();

			for (var i = 0; i < photos.length; i++) {
				let result = await new Photo(photos[i]).prepareForUpload();

				formData.append(`photos[${i}]`, result.photo);
				this.uploadedPhotos.push(result.preview);
			}

			await axios.post(`/api/profiles/${this.profile.id}/photos`, formData);
		},

		async uploadVideos (videos) {
			const formData = new FormData();

			for (var i = 0; i < videos.length; i++) {
				formData.append(`videos[${i}]`, videos[i]);
			}

			const { data } = await axios.post(`/api/profiles/${this.profile.id}/videos`, formData);
			this.uploadedVideos.push(data);
		},

		async saveProfile () {
			if (this.profile) {
				this.profile = await Spark.patch(`/api/profiles/${this.profile.id}`, this.form);
			} else {
				this.profile = await Spark.post('/api/profiles', this.form);
			}
		},

		async publishProfile () {
			await axios.post(`/api/profiles/${this.profile.id}/publish`);

			this.profile.published_at = 'now';
		},

		async unpublishProfile () {
			await axios.post(`/api/profiles/${this.profile.id}/unpublish`);

			this.profile.published_at = null;
		}
	}
});	