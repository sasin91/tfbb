<kiosk-create-recording inline-template>
	<div class="card">
		<div v-if="recording" class="card-header">
			<a :href="recording.link">Go to recording</a>
		</div>

		<div class="card-body">
			<div class="form-group">
		      	<input 
		      		type="file" 
		      		name="file_upload" 
		      		class="form-control-plaintext bg-none border-0" 
		      		accept="video/*" 
		      		multiple 
		      		@change="fileQueue.add($event.target.files)"
		      	>
			</div>

	        <form @submit.prevent>
				<div class="form-group row">
				    <label class="col-md-4 control-label">Category</label>
				    <div class="col-md-6">
				        <input type="text" class="form-control" name="category"
				               v-model="form.category"
				               :class="{'is-invalid': form.errors.has('category')}">
				        <span class="invalid-feedback" v-show="form.errors.has('category')">
				            @{{ form.errors.get('category') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">Title</label>
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
				    <label class="col-md-4 control-label">Summary</label>
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
				    <label class="col-md-4 control-label">Body</label>
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
</kiosk-create-recording>