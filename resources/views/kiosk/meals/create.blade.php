<kiosk-create-meal inline-template @created="addMeal">
	<div class="card p-2">
		<div class="card-header">
			{{__('Create a new meal')}}
		</div>
		<div class="card-body">
			<ajax-food-select ref="foods"></ajax-food-select>

			<hr class="divider divider--light"></hr>

	        <form @submit.prevent>
	        	@include('components.meals.meal-form-fields')

		        <div class="form-group row mb-0">
		            <div class="col-md-6 offset-md-4">
		                <button class="btn btn-primary" @click.prevent="store" :disabled="form.busy">
		                    <span v-if="form.busy">
		                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Creating')}}
		                    </span>

		                    <span v-else>
		                        <i class="fa fa-btn fa-check-circle"></i> {{__('Create')}}
		                    </span>
		                </button>
		            </div>
		        </div>	
			</form>	
		</div>
	</div>
</kiosk-create-meal>