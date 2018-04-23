- Laravel-friendships
- Laravel-talk

- Diets
	- goal:string
	- style:string
	- ideal_for:string['Powerlifters', 'Strongmen', 'BodyBuilders']
	- :hasMany('meals')
	- foods: hasManyThrough('meals')

- Meals
	- name:string
	- description:string
	- type:string['Breakfast', 'Lunch', 'Dinner', 'Snack']
	- allergies: hasManyThrough('foods')
	- Foods
		- allergies