import { photo, slugify } from '../../helpers'
import AjaxMealSelect from '../../ajax-meal-select.vue'
import FileUploadQueue from '../../file-upload-queue';

Vue.component('kiosk-create-diet', {
	components: {AjaxMealSelect},
	mixins: [
		require('../../mixins/diets/diet-editor'),
	],

	data () {
		return {
			form: new SparkForm({
				'goal': 'Muscle building', 
				'style': 'High protein',
				'title': '', 
				'slug': '',
				'summary': '', 
				'body': '',
				'view': 'diets.generic',
		        'banner_url': ''
			}),

			fileQueue: new FileUploadQueue([
				{ name: 'photos', mime: 'image', transformer: (file) => photo(file) },
				{ name: 'videos', mime: 'video' },
				{ name: 'documents', mime: 'application/pdf' }
			]),

			diet: null
		}
	},

	computed: {
		/**
		 * Build the default slug from the title.
		 * 
		 * @return string
		 */
		defaultSlug () {
			return slugify(this.form.title);
		}
	},

	methods: {
		async store () {
			this.diet = await Spark.post('/api/diets', this.form);

			this.$emit('created', this.diet);

			await axios.post(
				`/api/diets/${this.diet.slug}/meals`,
				{'meals': this.$refs.meals.selectedMeals.map(meal => meal.objectID)}
			);

			await this.fileQueue.upload((files, name) => {
				if (files.length === 0) {
					return;
				}

				return axios.post(`/api/diets/${this.diet.slug}/${name}`, files);
			});
		}
	}
});