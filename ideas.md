- Laravel-friendships
- Laravel-talk

- Diets
	- goal:string
	- style:string
	- ideal_for:string['Powerlifters', 'Strongmen', 'BodyBuilders']
	- allergies: hasManyThrough('foods')
	- Foods
		- allergies