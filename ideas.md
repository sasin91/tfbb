- Workout (Scheme?)
	- total_weeks:integer
	- goal:string

- WorkoutSchema
	- workout_id
	- type ['Deload', 'Max effort', 'Hypertrophy'...]
	- days:integer
	- week:integer

- WorkoutProgress
	- workout_scheme_id
	- profile_id
	- progress:unsignedInteger (Workout.total_weeks * WorkoutSchema.week / 100)