<template>
		<div class="modal fade" id="manage-offer-modal">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-body">
				        <form @submit.prevent>
							<div class="form-group row">
							    <label class="col-md-4 control-label">Name</label>
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
							    <label class="col-md-4 control-label">Tagline</label>
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
							    <label class="col-md-4 control-label">Discount</label>
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
							    <label class="col-md-4 control-label">Photo URL</label>
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
							    <label class="col-md-4 control-label">Banner URL</label>
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
							    <label class="col-md-4 control-label">Product URL</label>
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
							    <label class="col-md-4 control-label">View</label>
							    <div class="col-md-6">
							        <input type="text" class="form-control" name="view"
							               v-model="form.view"
							               :class="{'is-invalid': form.errors.has('view')}">
							        <span class="invalid-feedback" v-show="form.errors.has('view')">
							            @{{ form.errors.get('view') }}
							        </span>
							    </div>
							</div>				
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary">Save changes</button>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->
</template>

<script>
	export default {
		data () {
			return {
				offer: null,

				form: new SparkForm({
			    	name: '',
			    	tagline: '',
			    	discount: 0, 
			    	body: '',
			    	poster_url: '', 
			    	banner_url: '',
			    	offsite_link: '',

			        view: 'offers.generic'
				})
			}
		},

		methods: {
			show(offer) {
				this.offer = offer;

				$('#manage-offer-modal').modal('show');
			},

			update () {
				Spark.patch(`/api/offers/${this.offer.slug}`, this.form).then((offer) => this.offer = offer);
			}
		}
	}
</script>