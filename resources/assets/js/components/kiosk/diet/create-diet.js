import { slugify } from '../../helpers'
import { tap } from 'lodash'
import AjaxMealSelect from '../../ajax-meal-select.vue'

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

			diet: null
		}
	},

	computed: {
		defaultSlug () {
			return slugify(this.form.title);
		}
	},

	methods: {
		addToFilesArray (file) {
			//
		},

		removeFromFilesArray (file) {
			//
		},

		async store () {
			const diet = await Spark.post('/api/diets', this.form);
			this.diet = diet;
			
			const meals = this.$refs.meals.selectedMeals;

			if (meals.length > 0) {
				axios.post(
					diet.urls.api.meals.store,
					{'meals': meals.map(meal => meal.objectID)}
				);
			}
			
			tap(this.$refs.fileManager.pond, (pond) => {
				pond.setOptions({ server: { url: diet.urls.api.files.store }});
				pond.processFiles();
			});

			this.$emit('created', this.diet);
		}
	}
});