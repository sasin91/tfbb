<kiosk-create-workout inline-template>
	<div class="card">
		<div class="card-header">
			{{ __('Create a new workout') }}
		</div>
		<div class="card-body">
			<div class="form-group row">
				<label class="col-md-4 control-label">Upload files</label>
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

			<hr class="divider divider--light"></hr>

	        <form @submit.prevent>
				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Title') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="title"
				               v-model="form.title"
				               :class="{'is-invalid': form.errors.has('title')}">

				        <span class="invalid-feedback" v-show="form.errors.has('title')">
				            @{{ form.errors.get('title') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Level') }}</label>

				    <div class="col-md-6">
						<select 
							v-model="form.level" 
							name="level" 
							class="custom-select form-control" 
							:class="{'is-invalid': form.errors.has('level')}"
						>
						@foreach(config('training.levels') as $level)
							@if($loop->first)
						  		<option selected>{{ __($level) }}</option>
						  	@else
						  		<option>{{ __($level) }}</option>
						  	@endif
						@endforeach
						</select>
				       
				        <span class="invalid-feedback" v-show="form.errors.has('level')">
				            @{{ form.errors.get('level') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Style') }}</label>

				    <div class="col-md-6">
						<select 
							v-model="form.type" 
							name="type" 
							class="custom-select form-control" 
							:class="{'is-invalid': form.errors.has('type')}"
						>
						@foreach(config('training.styles') as $style)
							@if($loop->first)
						  		<option selected>{{ __($style) }}</option>
						  	@else
						  		<option>{{ __($style) }}</option>
						  	@endif
						@endforeach
						</select>

				        <span class="invalid-feedback" v-show="form.errors.has('type')">
				            @{{ form.errors.get('type') }}
				        </span>
				    </div>
				</div>	

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Summary') }}</label>

				    <div class="col-md-6">
				        <input  type="text" class="form-control" name="summary"
				                v-model="form.summary"
				                :class="{'is-invalid': form.errors.has('summary')}">

				        <span class="invalid-feedback" v-show="form.errors.has('summary')">
				            @{{ form.errors.get('summary') }}
				        </span>
				    </div>
				</div>	

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Body') }}</label>

				    <div class="col-md-6">
						<quill-editor
							v-model="form.body"
							ref="editor"
							:options="editorOptions"
						></quill-editor>

				        <span class="invalid-feedback" v-show="form.errors.has('body')">
				            @{{ form.errors.get('body') }}
				        </span>
				    </div>
				</div>	

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
</kiosk-create-workout>