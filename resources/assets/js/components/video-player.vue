<template>
	<video ref="player" controls preload="auto" :height="height" :width="width">
		<source :src="result">
  		Your browser does not support the video tag.
	</video>
</template>

<script>
	export default {
		props: {
			media: { type: Object|String },
			width: { type: Number, default: 368 },
			height: { type: Number, default: 232 }
		},

		data () {
			return {
				result: ''
			}
		},

		mounted () {
			// When given a string, assume its a URL.
			if (typeof this.media === 'string') {
				this.result = this.media;
			} else {
				this.result = URL.createObjectURL(this.media);
			}
		},

		beforeDestroy () {
			URL.revokeObjectURL(this.result);
		},

		methods: {
			supports (media) {
				if (typeof media === 'object') {
					return media.type.includes('video');
				}

				return media.includes('video');
			}
		}
	}
</script>