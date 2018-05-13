import { slugify } from '../../helpers'
import AjaxFoodSelect from '../../ajax-food-select.vue'

Vue.component('kiosk-create-meal', {
	components: {AjaxFoodSelect},

	mixins: [require('../../mixins/meals/meal-editor')],

	data () {
		return {
			form: new SparkForm({
				'type': '',
				'name': '', 
				'slug': '',
				'description': '',
				'photo_url': '',
			}),

			meal: null
		}
	},

	computed: {
		defaultSlug () {
			return slugify(this.form.name);
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
			const meal = await Spark.post('/api/meals', this.form);
			this.meal = meal;
			
			const foods = this.$refs.foods.selectedFoods;
			console.log(foods.length, foods.length > 0);
			if (foods.length > 0) {
				console.log(foods, meal);
				axios.post(
					meal.urls.api.foods.store,
					{'foods': foods.map(food => food.ndbno)}
				);
			}

			this.$emit('created', this.meal);
		}
	}
});