<kiosk-create-diet inline-template @created="addDiet">
	<div class="card p-2">
		<div class="card-header">
			{{__('Create a new diet')}}
		</div>
		<div class="card-body">
			<div class="form-group">
				<file-manager 
					ref="fileManager"
					:upload-immediately="false" 
					input-id="create-diet-filepond"
					@uploaded="addToFilesArray"
					@dropped="removeFromFilesArray"
				>
				   	<div class="col-md-12" slot-scope="{}">
				   		<input
				   			id="create-diet-filepond" 
				    		ref="fileInput"
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
				</file-manager>
			</div>

			<div class="form-group row">
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