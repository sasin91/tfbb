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
							        <input type="text" class="form-control" name="poster_url"
							               v-model="form.poster_url"
							               :class="{'is-invalid': form.errors.has('poster_url')}">
							        <span class="invalid-feedback" v-show="form.errors.has('poster_url')">
							            @{{ form.errors.get('poster_url') }}
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
						<a v-if="offer" class="btn btn-link" target="_blank" :href="offer.links.self">Go to offer</a>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-primary" @click.prevent="update">Save changes</button>
						<button type="button" class="btn btn-danger" @click.prevent="destroy">Delete offer</button>
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

		mounted () {
			const self = this;

			$('#manage-offer-modal').on('hidden.bs.modal', function (e) {
				self.blank();
			});
		},

		destroyed () {
			this.blank();
		},

		methods: {
			show (offer) {
				this.offer = offer;

				this.form.name = offer.name;
				this.form.tagline = offer.tagline;
				this.form.discount = offer.discount;
				this.form.body = offer.body;
				this.form.poster_url = offer.poster_url;
				this.form.banner_url = offer.banner_url;
				this.form.offsite_link = offer.links.product;
				this.form.view = offer.view;

				$('#manage-offer-modal').modal('show');
			},

			blank () {
				this.form.name = '';
				this.form.tagline = '';
				this.form.discount = '';
				this.form.body = '';
				this.form.poster_url = '';
				this.form.banner_url = '';
				this.form.offsite_link = '';
				this.form.view = '';
			},

			destroy () {
				axios.delete(`/api/offers/${this.offer.slug}`).then(() => {
					this.blank();
					$('#manage-offer-modal').modal('hide');
				});
			},

			update () {
				Spark.patch(`/api/offers/${this.offer.slug}`, this.form).then((offer) => this.offer = offer);
			}
		}
	}
</script>