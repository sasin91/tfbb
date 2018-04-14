<kiosk-manage-recordings inline-template>
	<div>
		<nav class="nav nav-pills nav-justified kiosk-manage-recordings-tabs" role="tablist">
			<li class="nav-item">
				<a href="#/recordings" class="nav-link" @click.prevent="currentTab = 'create'">{{ __('Create') }}</a>
			</li>
			<li class="nav-item">
				<a href="#/recordings" class="nav-link" @click.prevent="currentTab = 'list'">{{ __('List') }}</a>
			</li>
		</nav>

		<div class="tab-content">
			<div v-show="currentTab == 'create'" role="tabpanel" class="tab-pane" :class="{ active: currentTab == 'create' }" id="create">
				@include('kiosk.recordings.create')
			</div>

			<div v-show="currentTab == 'list'" role="tabpanel" class="tab-pane" :class="{ active: currentTab == 'list' }" id="list">
				@include('kiosk.recordings.index')
			</div>
		</div>

		<manage-recording-modal ref="manageRecordingModal"></manage-recording-modal>
	</div>
</kiosk-manage-recordings>