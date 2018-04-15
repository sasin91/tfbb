import FileUploadQueue from '../../file-upload-queue'
import { photo } from '../../helpers'

Vue.component('kiosk-create-workout', {
	data () {
		return {
			workout: {},

			form: new SparkForm({
				title: '',
				type: 'BodyBuilding',
				level: 'Beginner',
				summary: '',
				body: ''
			}),

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
					]
				} 
			},

			fileQueue: new FileUploadQueue([
				{ name: 'photos', mime: 'image', transformer: (file) => photo(file) },
				{ name: 'videos', mime: 'video' },
				{ name: 'documents', mime: 'application/pdf' }
			])
		}
	},

	methods: {
		store () {
			Spark.post('/api/workouts', this.form)
				.then((workout) => {
					this.workout = workout;
					this.fileQueue.upload((files, name) => {
						if (files.length > 0) {
							return axios.post(`/api/workouts/${this.workout.slug}/${name}`, files);
						}
					});

					swal('Done', 'The workout has been created!', 'success')
				})
		}
	}
});