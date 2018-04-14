<template>
	<div class="modal fade" id="photo-modal">
		<div class="modal-dialog" role="document">
			<div class="modal-content bg-transparent border-0">
				<div>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
						<span class="sr-only">Close</span>
					</button>
				</div>
				<div class="modal-body">
					<img :src="photo" class="img-fluid">
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</template>

<script>
	export default {
		data () {
			return {
				photo: ''
			}
		},

		mounted () {
			const self = this

			Bus.$on('PhotoModal.show', (photo) => {
				if (typeof photo === 'object') {
					self.photo = photo.url;
				} else {
					self.photo = photo;
				}

				self.show();
			});
		},

		methods: {
			show () {
				$('#photo-modal').modal('show')
			},

			hide () {
				$('#photo-modal').modal('hide')
			}
		}
	}
</script>