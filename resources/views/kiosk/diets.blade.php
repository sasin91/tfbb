<kiosk-manage-diets inline-template>
	<div>
		<div class="btn-group" role="group" aria-label="Actions">
			<button type="button" class="btn btn-primary" @click="creatingDiet = true">Create</button>
			<button v-show="selectedDiet" type="button" class="btn btn-primary" @click="selectedDiet = null">Back</button>
		</div>

		<transition name="fade">
			<div v-show="creatingDiet">
				@include('kiosk.diets.create')
			</div>
		</transition>

		<transition name="fade">
			<div v-show="selectedDiet">
				@include('kiosk.diets.edit')
			</div>
		</transition>

		<transition name="fade">
			<div v-show="! selectedDiet">
				@include('kiosk.diets.search')
				@include('kiosk.diets.list')
			</div>
		</transition>
	
	</div>
</kiosk-manage-diets>