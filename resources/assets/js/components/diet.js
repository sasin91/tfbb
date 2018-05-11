import { uniq } from 'lodash';

Vue.component('diet', {
	props: { diet:Object, user:Object },

	mounted () {
		this.enablePopOverTooltips();
	},

	methods: {
		 startDiet () {
			axios.post(
				'/diet',
				{ diet_id: this.diet.id }, 
				{ headers: { accept: 'application/json' } }
			).then(() => {
				swal({
				  title: "Awesome",
				  text: `You have started ${this.diet.title}, enjoy!`,
				  type: "success",
				  showCancelButton: false,
				  closeOnConfirm: false,
				  html: false
				}, function(){
				  location.reload()
				});
			})			
		},

		enablePopOverTooltips () {
			$(function () {
			  $('[data-toggle="popover"]').popover({
			  	container: 'body',
			  	placement: 'top',
			  	trigger: 'hover',
			  	animation: true
			  })
			})
		}
	}
});