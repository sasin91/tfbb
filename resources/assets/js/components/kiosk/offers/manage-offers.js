import ManageOfferModal from './manage-offer-modal.vue';

Vue.component('kiosk-manage-offers', {
	components: {ManageOfferModal},

	mounted () {
		const self = this;

        Bus.$on('sparkHashChanged', function (hash, parameters) {
            if (hash == 'offers' && self.offers.length === 0) {
            	self.fetchOffers();
            	self.listenForNewOffers();
            }

            return true;
        });
	},

	data () {
		return {
			currentPage: null,
			lastPage: null,
			sortColumn: null,
			sortDirection: 'desc',

			offers: []
		}
	},

	computed: {
		pages () {
			return Array.from(Array(this.lastPage+1).keys()).slice(1);
		},

		sortIcon () {
			return this.sortDirection === 'asc' ? 'fa-arrow-down' : 'fa-arrow-up';
		}
	},

	methods: {
		sortBy (column) {
			if (column == this.sortColumn) {
				this.sortDirection = (this.sortDirection === 'desc') ? 'asc' : 'desc';
			}

			this.sortColumn = column;

			if (this.searchForm.query.length > 0) {
				this.searchOffers();
			} else {
				this.fetchOffers();
			}
		},

		fetchNextOffers () {
			this.currentPage += this.currentPage;

			this.fetchOffers();
		},

		fetchPreviousOffers () {
			this.currentPage = this.currentPage -1;

			this.fetchOffers();
		},

		fetchOffers (page = null) {
			axios.get('/api/offers', {
				params: { 
					page: (page ? page : this.currentPage), 
					sortBy: this.sortColumn,
					sortDirection: this.sortDirection
				},
				headers: { Accept: 'Application/json' }
			}).then(({ data }) => {
				this.currentPage = data.meta.current_page;
				this.lastPage = data.meta.last_page;

				this.offers = data.data;
				this.listenForEvents();
			});
		},

		showOffer (offer) {
			this.$refs.manageOfferModal.show(offer);
		},

		showNewOfferModal () {
			this.$refs.newOfferModal.show();
		},

		listenForNewOffers () {
			Echo.channel('App.Offer')
				.listen('Offer.OfferCreated', (event) => {
					if (! this.offers.includes(event.offer)) {
						this.offers.unshift(event.offer);
					}
				})
		},

		listenForEvents () {
			const self = this;

			this.offers.forEach(function (offer) {
				Echo.channel(`App.Offer.${offer.id}`)
					.listen('Offer.OfferUpdated', (event) => {
						const index = self.offers.findIndex(offer => parseInt(offer.id) === parseInt(event.offer.id))
						
						self.$set(self.offers, index, event.offer);
					})
					.listen('Offer.OfferDeleted', (event) => {
						self.offers = self.offers.filter(offer => parseInt(offer.id) !== parseInt(event.offer.id));
					})
			});
		}
	}
});