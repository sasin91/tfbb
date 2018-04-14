import FileUploadQueue from '../../file-upload-queue';

Vue.component('kiosk-create-recording', {
	data () {
		return {
			recording: null,

			form: new SparkForm({
				category: '', 
				title: '', 
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
				{ name: 'videos', mime: 'video' }
			])
		}
	},

	methods: {
		async store () {
			this.recording = await Spark.post('/api/recordings', this.form);

			this.fileQueue.upload((files, name) => {
				if (files.length > 0) {
					return axios.post(`/api/recordings/${this.recording.slug}/${name}`, files);
				}
			});
		}
	}
});