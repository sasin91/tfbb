<kiosk-manage-offers inline-template>
	<div>
		@include('kiosk.offers.create')
		@include('kiosk.offers.index')

		<manage-offer-modal ref="manageOfferModal"></manage-offer-modal>
	</div>
</kiosk-manage-offers>