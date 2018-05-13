import { photo, slugify } from '../../helpers'
import AjaxMealSelect from '../../ajax-meal-select.vue'

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
		}
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

				this.$refs.fileManager.pond.setOptions({
					server: { url: diet.urls.api.files.store }
				});
			},

			immediate: false,
			deep: false
		}
	},

	computed: {
		defaultSlug () {
			return slugify(this.form.title);
		}
	},

	methods: {
		addToFilesArray (file) {
			this.files.push(file);			
		},

		removeFromFilesArray (file) {
			console.log('Removing file...');
			console.log(file);
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