Vue.component('kiosk-create-offer', {
	data () {
		return {
			offer: null,

			form: new SparkForm({
		    	name: '',
		    	tagline: '',
		    	discount: 0, 
		    	body: '',
		    	poster_url: '', 
		    	banner_url: '',
		    	offsite_link: '',

		        view: 'offers.generic'
			})
		}
	},

	methods: {
		store () {
			Spark.post('/api/offers', this.form).then((offer) => this.offer = offer);
		}
	}
});