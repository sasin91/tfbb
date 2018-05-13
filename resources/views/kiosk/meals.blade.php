<kiosk-manage-meals inline-template>
	<div>
		<div class="btn-group" role="group" aria-label="Actions">
			<button type="button" class="btn btn-primary" 
				v-show="! creatingMeal" 
				@click="creatingMeal = true"
			>{{ __('Create') }}</button>
			<button type="button" class="btn btn-primary" 
				v-show="creatingMeal" 
				@click="creatingMeal = false"
			>{{ __('Back') }}</button>

			<button type="button" class="btn btn-primary" 
				v-show="selectedMeal"
				@click="selectedMeal = null"
			>{{ __('Back') }}</button>
		</div>

		<transition name="fade">
			<div v-show="! selectedMeal && ! creatingMeal">
				@includeIf('kiosk.meals.search')
				@includeIf('kiosk.meals.list')
			</div>
		</transition>		

		<transition name="fade">
			<div v-if="creatingMeal">
				@includeIf('kiosk.meals.create')
			</div>
		</transition>

		<transition name="fade">
			<div v-if="selectedMeal">
				@includeIf('kiosk.meals.edit')
			</div>
		</transition>
	</div>
</kiosk-manage-meals>