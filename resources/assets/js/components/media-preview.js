import { find } from 'lodash';

import PhotoPreview from './photo-preview.vue'
import VideoPlayer from './video-player.vue'

Vue.component('media-preview', {
	functional: true,

	props: { media:Object },

	render (createElement, context) {
		const component = find([PhotoPreview, VideoPlayer], comp => comp.methods.supports(context.props.media));

		return createElement(component, {
			props: { url: context.props.media.url },

			staticClass: context.data.staticClass,
			attrs: context.data.attrs,
			key: context.data.key,
		});
	}
})