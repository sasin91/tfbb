import { photo, slugify, cookie, fileFromUrl } from './helpers'

import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilepondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilepondPluginImageResize from 'filepond-plugin-image-resize';
import FilepondPluginFileValidateType from 'filepond-plugin-file-validate-type';
FilePond.registerPlugin(FilepondPluginImagePreview);
FilePond.registerPlugin(FilepondPluginImageResize); 
FilePond.registerPlugin(FilepondPluginFileValidateType);

Vue.component('file-manager', {
	props: { 
		url: { type:String, required: false, default: './' }, 
		inputId: { type:String, required: false, default: '' } ,
		uploadImmediately: { type:Boolean, required: false, default: true }
	},

	render () {
		return this.$scopedSlots.default({
			pond: this.pond,
			createPond: this.createPond
		});
	},

	mounted () {
		if (this.inputId !== '') {
			// this.createPond(document.getElementById(this.inputId));
			this.pond = this.createPond(this.$el.children.namedItem(this.inputId));
		}
	},

	data () {
		return {
			headers: {
				'X-Requested-With': 'XMLHttpRequest',
   				'X-CSRF-TOKEN': Spark.csrfToken,
   				'X-XSRF-TOKEN': cookie('XSRF-TOKEN')
			},

			pond: null
		}
	},
	
	methods: {
		upload () {
			if (this.pond) {
				return this.pond.processFiles();
			}
		},

		createPond (element) {
			const filePond = FilePond.create(element);

			filePond.setOptions({
				dropValidation: true,

				instantUpload: this.uploadImmediately,

				server: {
					url: this.url,
					process: { headers: this.headers },
					revert: { headers: this.headers }
				},

				onprocessfile: (error, uploaded) => {
					if (! error) {
						this.$emit('uploaded', uploaded.file);
					}
				},

				onremovefile: (file) => this.$emit('dropped', file.file)
			});

			return filePond;
		}
	}
});