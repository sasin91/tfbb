import { uniq } from 'lodash';

Vue.component('workout', {
	props: { workout:Object, user:Object },

	computed: {
		equipment () {
			let equipment = this.workout.exercises.map(exercise => exercise.equipment);

			return uniq(equipment);
		},

		muscles () {
			let muscles = this.workout.exercises.map(exercise => exercise.muscles);

			return uniq(muscles);
		}
	},

	methods: {
		 startWorkout () {
			axios.post(
				'/user/current/workout',
				{ workout_id: this.workout.id }, 
				{ headers: { accept: 'application/json' } }
			).then(() => {
				swal({
				  title: "Awesome",
				  text: `You have started ${this.workout.title}, enjoy!`,
				  type: "success",
				  showCancelButton: false,
				  closeOnConfirm: false,
				  html: false
				}, function(){
				  location.reload()
				});
			})			
		}
	}
});