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
				    <label class="col-md-4 control-label">{{ __('Style') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="style"
				               v-model="form.style"
				               :class="{'is-invalid': form.errors.has('style')}"
				        >

				        <span class="invalid-feedback" v-show="form.errors.has('style')">
				            @{{ form.errors.get('style') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Goal') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="goal"
				               v-model="form.goal"
				               :class="{'is-invalid': form.errors.has('goal')}"
				        >

				        <span class="invalid-feedback" v-show="form.errors.has('goal')">
				            @{{ form.errors.get('goal') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Blade view name') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="view"
				               v-model="form.view"
				               :class="{'is-invalid': form.errors.has('view')}"
				        >

				        <span class="invalid-feedback" v-show="form.errors.has('view')">
				            @{{ form.errors.get('view') }}
				        </span>
				    </div>
				</div>

				<div class="form-group row">
				    <label class="col-md-4 control-label">{{ __('Banner URL') }}</label>

				    <div class="col-md-6">
				        <input type="text" class="form-control" name="banner_url"
				               v-model="form.banner_url"
				               :class="{'is-invalid': form.errors.has('banner_url')}">

				        <span class="invalid-feedback" v-show="form.errors.has('banner_url')">
				            @{{ form.errors.get('banner_url') }}
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