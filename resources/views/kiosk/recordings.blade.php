<kiosk-manage-recordings inline-template>
	<div>
		<div class="btn-group" role="group" aria-label="Actions">
			<button type="button" class="btn btn-primary" 
				v-show="! creatingRecording" 
				@click="creatingRecording = true"
			>{{ __('Create') }}</button>
			<button type="button" class="btn btn-primary" 
				v-show="creatingRecording" 
				@click="creatingRecording = false"
			>{{ __('Back') }}</button>

			<button type="button" class="btn btn-primary" 
				v-show="selectedRecording"
				@click="selectedRecording = null"
			>{{ __('Back') }}</button>
		</div>

		<transition name="fade">
			<div v-show="! selectedRecording && ! creatingRecording">
				@includeIf('kiosk.recordings.search')
				@includeIf('kiosk.recordings.index')
			</div>
		</transition>		

		<transition name="fade">
			<div v-if="creatingRecording">
				@includeIf('kiosk.recordings.create')
			</div>
		</transition>

		<transition name="fade">
			<div v-if="selectedRecording">
				@includeIf('kiosk.recordings.edit')
			</div>
		</transition>
	</div>
</kiosk-manage-recordings>