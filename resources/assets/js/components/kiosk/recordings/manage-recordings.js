import ManageRecordingModal from './manage-recording-modal.vue';

Vue.component('kiosk-manage-recordings', {
	components: {ManageRecordingModal},

	data () {
		return {
			currentTab: 'create'
		}
	}
})