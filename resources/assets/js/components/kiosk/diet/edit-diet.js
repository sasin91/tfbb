import { photo, slugify, cookie, fileFromUrl } from '../../helpers'
import AjaxMealSelect from '../../ajax-meal-select.vue'

import * as FilePond from 'filepond';
import 'filepond/dist/filepond.min.css';
import FilepondPluginImagePreview from 'filepond-plugin-image-preview';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css';
import FilepondPluginImageResize from 'filepond-plugin-image-resize';
import FilepondPluginFileValidateType from 'filepond-plugin-file-validate-type';

FilePond.registerPlugin(FilepondPluginImagePreview);
FilePond.registerPlugin(FilepondPluginImageResize); 
FilePond.registerPlugin(FilepondPluginFileValidateType);

Vue.component('kiosk-edit-diet', {
	components: {AjaxMealSelect},

	mixins: [
		require('../../mixins/diets/diet-editor'),
	],

	props: { diet:Object },

	data () {
		return {
			form: new SparkForm({
				'goal': '', 
				'style': '',
				'title': '', 
				'slug': '',
				'summary': '', 
				'body': '',
				'view': '',
		        'banner_url': ''
			}),

			files: [],
			pond: null,
		}
	},

	mounted () {
		this.pond = FilePond.create(this.$refs.fileUpload);
		// this.pond.setOptions({});
	},

	watch: {
		diet: {
			handler: function (diet) {
				this.form.goal = diet.goal;
				this.form.style = diet.style;
				this.form.title = diet.title;
				this.form.slug = diet.slug;
				this.form.summary = diet.summary;
				this.form.body = diet.body;
				this.form.view = diet.view;
				this.form.banner_url = diet.banner_url;

				this.files = diet.files;

				this.pond.setOptions({
					server: {
					    url: diet.urls.api.files.store,
					    process: {
					    	headers: {
							    'X-Requested-With': 'XMLHttpRequest',
	   							'X-CSRF-TOKEN': Spark.csrfToken,
	   							'X-XSRF-TOKEN': cookie('XSRF-TOKEN')
					    	}
					    }
					},

					onprocessfile: (error, uploaded) => {
						if (! error) {
							this.files.push(uploaded.file);
						}
					}
				});
			},

			immediate: false,
			deep: true
		}
	},

	computed: {
		defaultSlug () {
			return slugify(this.form.title);
		}
	},

	methods: {
		async edit (diet) {
			const { data } = await axios.get(this.diet.urls.api.show);
			
			data.files.map(url => fileFromUrl(url));

			this.selectedDiet = data;
		},

		async update () {
			const diet = await Spark.patch(`/api/diets/${this.diet.slug}`, this.form);
			this.$emit('updated', { diet: diet });

			await axios.post(
				`/api/diets/${diet.slug}/meals`,
				{'meals': this.$refs.meals.selectedMeals.map(meal => meal.objectID)}
			);

			await this.fileQueue.upload((files, name) => {
				if (files.length === 0) {
					return;
				}

				return axios.post(`/api/diets/${diet.slug}/${name}`, files);
			});
		}
	}
});