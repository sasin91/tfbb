import { find } from 'lodash';

import PhotoPreview from './photo-preview.vue'
import VideoPlayer from './video-player.vue'

Vue.component('media-preview', {
	functional: true,

	props: { media:Object|File },

	render (createElement, context) {
		const component = find([PhotoPreview, VideoPlayer], comp => comp.methods.supports(context.props.media));
		const media = context.props.media;

		const mediaUrl = (media instanceof File) ? URL.createObjectURL(media) : media.url;

		return createElement(component, {
			props: { media: mediaUrl },

			staticClass: context.data.staticClass,
			attrs: context.data.attrs,
			key: context.data.key,
		});
	}
})