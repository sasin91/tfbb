- Laravel-friendships
- Laravel-talk

- Diets
	- goal:string
	- style:string
	- ideal_for:string['Powerlifters', 'Strongmen', 'BodyBuilders']
	- :hasMany('meals')
	- foods: hasManyThrough('meals')
	- Generates PDF

- Meals
	- name:string
	- description:string
	- type:string['Breakfast', 'Lunch', 'Dinner', 'Snack']
	- allergies: hasManyThrough('foods')
	- :hasMany('Foods')

	@JS
		- query api.nal.usda.gov/ndb through /api/food-search/usda?query=${query}
			- food-search leverages guzzle cache-middleware

			- push result into memory through Vue component
			
			- post searchResults to /api/meals/{meal}/foods-from-usda after creating meal
				-> api checks foods_table for ndbno as provider_id, associate if found.
				otherwise api dispatches request to api.nal.usda.gov/ndb/reports/V2 for the ndbno and creates new food record from response.