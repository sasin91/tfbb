<template>
	<img @click="display" v-if="ready" :src="encoded" class="clickable" :height="height" :width="width">
</template>

<script>
	export default {
		props: {
			url: { type: String },
			photo: { type: Object|String },
			width: { type: Number, default: 368 },
			height: { type: Number, default: 232 }
		},

		data () {
			return {
				encoded: null
			}
		},

		computed: {
			ready () {
				return this.encoded && this.encoded.length !== 0;
			}
		},

		mounted () {
			if (this.url) {
				this.encoded = this.url;
			}

			if (typeof this.photo === 'object') {
				if (this.photo.hasOwnProperty('file')) {
					this.base64Encode(this.photo.file).then((result) => {
						this.encoded = result;
					});
				} else {
					if (this.photo.hasOwnProperty('preview')) {
						this.encoded = this.photo.preview;
					} else {
						this.encoded = this.photo.thumbnail ? this.photo.thumbnail : this.photo.url;
					}

				}
			}

			if (typeof this.photo === 'string') {
				// When a string is given, we can assume its either a data string or url.
				this.encoded = this.photo;
			}
		},

		methods: {
			supports (media) {
				if (typeof media === 'object') {
					return media.mime_type.includes('image');
				}

				return media.includes('image');
			},

			display () {
				if (typeof this.photo === 'object') {
					if (this.photo.original) {
						this.base64Encode(this.photo.original).then(result => Bus.$emit('PhotoModal.show', result))
					} else if(this.photo.url) {
						Bus.$emit('PhotoModal.show', this.photo.url)
					}
				} else {
					Bus.$emit('PhotoModal.show', this.encoded);
				}
			},

			base64Encode (file) {
				return new Promise((resolve, reject) => {
					if (typeof file.toDataURL === 'function') {
						return resolve(file.toDataURL());
					}

					const reader = new FileReader;

				    reader.readAsDataURL(file);
				    reader.onload = () => resolve(reader.result);
				    reader.onerror = error => reject(error);
				});
			},
		}
	}
</script>