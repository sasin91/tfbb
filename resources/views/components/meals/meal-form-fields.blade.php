				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Name') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="name"
				               v-model="form.name"
				               :class="{'is-invalid': form.errors.has('name')}">

				        <span class="invalid-feedback" v-show="form.errors.has('name')">
				            @{{ form.errors.get('name') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('URL key') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="slug"
				               v-model="form.slug"
				               :class="{'is-invalid': form.errors.has('slug')}"
				               :placeholder="defaultSlug">

				        <span class="invalid-feedback" v-show="form.errors.has('slug')">
				            @{{ form.errors.get('slug') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Type') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="type"
				               v-model="form.type"
				               :class="{'is-invalid': form.errors.has('type')}"
				        >

				        <span class="invalid-feedback" v-show="form.errors.has('type')">
				            @{{ form.errors.get('type') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Photo URL') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="photo_url"
				               v-model="form.photo_url"
				               :class="{'is-invalid': form.errors.has('photo_url')}">

				        <span class="invalid-feedback" v-show="form.errors.has('photo_url')">
				            @{{ form.errors.get('photo_url') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Description') }}</label>

				    <div class="col-md-6">
						<quill-editor
							v-model="form.description"
							ref="editor"
							:options="editorOptions"
						></quill-editor>

				        <span class="invalid-feedback" v-show="form.errors.has('description')">
				            @{{ form.errors.get('description') }}
				        </span>
				    </div>
				</div>	