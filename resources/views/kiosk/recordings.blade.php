<kiosk-manage-recordings inline-template>
	<div>
		@include('kiosk.recordings.create')
		@include('kiosk.recordings.index')

		<manage-recording-modal ref="manageRecordingModal"></manage-recording-modal>
	</div>
</kiosk-manage-recordings>