<kiosk-manage-diets inline-template>
	<div>
		<div class="btn-group" role="group" aria-label="Actions">
			<button type="button" class="btn btn-primary" 
				v-show="! creatingDiet" 
				@click="creatingDiet = true"
			>{{ __('Create') }}</button>
			<button type="button" class="btn btn-primary" 
				v-show="creatingDiet" 
				@click="creatingDiet = false"
			>{{ __('Back') }}</button>

			<button type="button" class="btn btn-primary" 
				v-show="selectedDiet"
				@click="selectedDiet = null"
			>{{ __('Back') }}</button>
		</div>

		<transition name="fade">
			<div v-show="! selectedDiet && ! creatingDiet">
				@include('kiosk.diets.search')
				@include('kiosk.diets.list')
			</div>
		</transition>		

		<transition name="fade">
			<div v-if="creatingDiet">
				@include('kiosk.diets.create')
			</div>
		</transition>

		<transition name="fade">
			<div v-if="selectedDiet">
				@include('kiosk.diets.edit')
			</div>
		</transition>
	
	</div>
</kiosk-manage-diets>