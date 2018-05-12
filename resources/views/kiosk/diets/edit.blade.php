<kiosk-edit-diet inline-template :diet="selectedDiet" @updated="creatingDiet = false">
	<div class="card p-2">
		<div class="card-body">
			<div class="form-group">
				<div class="col-md-12">
			      	<input 
			      		ref="fileUpload"
			      		type="file" 
			      		name="file" 
			      		class="form-control-plaintext bg-none border-0 filepond" 
			      		accept="image/*, video/*, application/pdf" 
			      		multiple 
			      	>
			      	<small class="lead">
			      		{{ __('For videos,  MP4 format is preferred, as it has wider native support.') }}
			      	</small>
				</div>

				<hr class="divider"></hr>
				<div class="container">
					<media-preview class="p-1" v-for="file in files" :key="file.id" :media="file"></media-preview>
				</div>
			</div>

			<div class="form-group row">
				<ajax-meal-select ref="meals"></ajax-meal-select>
			</div>

			<hr class="divider divider--light"></hr>

	        <form @submit.prevent>
	        	@include('components.diets.diet-form-fields')

		        <div class="form-group row mb-0">
		            <div class="col-md-6 offset-md-4">
		                <button class="btn btn-primary" @click.prevent="update" :disabled="form.busy">
		                    <span v-if="form.busy">
		                        <i class="fa fa-btn fa-spinner fa-spin"></i> {{__('Updating')}}
		                    </span>

		                    <span v-else>
		                        <i class="fa fa-btn fa-check-circle"></i> {{__('Update')}}
		                    </span>
		                </button>
		            </div>
		        </div>	
			</form>	
		</div>
	</div>
</kiosk-edit-diet>