<kiosk-manage-workouts inline-template>
	<div>
		@include('kiosk.workouts.search')

		@include('kiosk.workouts.create')

		@include('kiosk.workouts.list')

		<manage-workout-modal ref="manageWorkoutModal" :config="{{ json_encode(config('training')) }}"></manage-workout-modal>
	</div>
</kiosk-manage-workouts>