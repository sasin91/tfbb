<kiosk-create-diet inline-template @created="creatingDiet = false">
	<div class="card p-2">
		<div class="card-header">
			{{__('Create a new diet')}}
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-md-4 control-label">{{__('Upload files')}}</label>
				<div class="col-md-6">
			      	<input 
			      		type="file" 
			      		name="file_upload" 
			      		class="form-control-plaintext bg-none border-0" 
			      		accept="image/*, video/*, application/pdf" 
			      		multiple 
			      		@change="fileQueue.add($event.target.files)"
			      	>
				</div>
			</div>

			<div class="form-group row">
				{{-- <label class="col-md-4 control-label">{{__('Select meals')}}</label> --}}
				<ajax-meal-select ref="meals"></ajax-meal-select>
			</div>

			<hr class="divider divider--light"></hr>

	        <form @submit.prevent>
	        	@include('components.diets.diet-form-fields')

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
</kiosk-create-diet>