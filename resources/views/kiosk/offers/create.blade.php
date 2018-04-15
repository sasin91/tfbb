<kiosk-create-offer inline-template>
	<div class="card">
		<div class="card-header">
			Create a new offer
			<a v-if="offer" :href="offer.links.self">Go to offer</a>
		</div>

        <form @submit.prevent>
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
			    <label class="col-md-4 control-label">{{ __('Tagline') }}</label>
			    <div class="col-md-6">
			        <input type="text" class="form-control" name="tagline"
			               v-model="form.tagline"
			               :class="{'is-invalid': form.errors.has('tagline')}">
			        <span class="invalid-feedback" v-show="form.errors.has('tagline')">
			            @{{ form.errors.get('tagline') }}
			        </span>
			    </div>
			</div>

			<div class="form-group row">
			    <label class="col-md-4 control-label">{{ __('Discount') }}</label>
			    <div class="col-md-6">
			        <input type="number" class="form-control" name="discount"
			               v-model="form.discount"
			               :class="{'is-invalid': form.errors.has('discount')}">
			        <span class="invalid-feedback" v-show="form.errors.has('discount')">
			            @{{ form.errors.get('discount') }}
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
			    <label class="col-md-4 control-label">{{ __('Product URL') }}</label>
			    <div class="col-md-6">
			        <input type="text" class="form-control" name="offsite_link"
			               v-model="form.offsite_link"
			               :class="{'is-invalid': form.errors.has('offsite_link')}">
			        <span class="invalid-feedback" v-show="form.errors.has('offsite_link')">
			            @{{ form.errors.get('offsite_link') }}
			        </span>
			    </div>
			</div>

			<div class="form-group row">
			    <label class="col-md-4 control-label">{{ __('View') }}</label>
			    <div class="col-md-6">
			        <input type="text" class="form-control" name="view"
			               v-model="form.view"
			               :class="{'is-invalid': form.errors.has('view')}">
			        <span class="invalid-feedback" v-show="form.errors.has('view')">
			            @{{ form.errors.get('view') }}
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
</kiosk-create-offer>