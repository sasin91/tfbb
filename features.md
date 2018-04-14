Board
	** RouteKey => hashid

	- Auth
		- + Socialite for Facebook

	- Live chat
		- *Socialite
		- Defer to facebook (iframe or "element" ?)

	- Recordings 
		* videos that doesn't quite fit anywhere else, but is still relevant.
		- category:string:index ('live chats', 'life')
		- hasMedia('videos')
		- title:string, slug, routesUsingSlug,
		- summary:string

	- PerformanceIndicator extensions
		- top posts.count
		- count users
		- count (users) => last_seen_at

	Remote API
		- wger.de
		- cached requests / responses (https://github.com/Kevinrob/guzzle-cache-middleware)

	- Workout
		- hasMedia('docs', 'videos', 'photos')
		- title, slug, routesUsingSlug
		- summary
		- body
		- level:string:index ('Beginner', 'Intermediate', 'Advanced', 'Elite')
		- type:string:index ('Bodybuilding', 'Powerlifting', 'Weight lifting', 'Hybrid')
		- Integrates with a remote API ('wger.de')
		- Ability to scope workouts matching given users profile

	- Workout of the month (WOTM)
		- A navbar link to a selected workout
		- A workoutSchedule model
		- starts_at:timestamp
		- ends_at:timestamp
		- workout_id:foreign

	- Exercise
		- hasMedia('videos', 'photos'),
		- title, slug, routesUsingSlug,
		- recap:string, basically a summary of description
		- description:text
		- caveats:string ?

	- Diet
		- hasMedia('docs', 'videos', 'photos')
		- title, slug, routesUsingSlug
		- summary
		- body
		- goal:string:index ('Fat loss', 'Muscle building')
		- type:string:index ('Ketogenic', 'High protein', 'Low carb', High carb')
		- Integrates with a remote API ('wger.de')

	- Offers
		- <<< Searchable
		- ^Devs
		- poster_url:string
		- off-site links
		- *testimonials

	- Testimonials
		- user_id:nullable
		- reviewer:string [The user name]
		- title:string
		- body:string
		- *photos

 	- Design
 		- Stick with existing
 		- Sprinkle ideas from basecamp 3

 	- kiosk
 		- Document uploads through FilePond
 		- Select WOTM



