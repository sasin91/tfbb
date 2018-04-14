
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Bootstrap
 |--------------------------------------------------------------------------
 |
 | First, we will load all of the "core" dependencies for Spark which are
 | libraries such as Vue and jQuery. This also loads the Spark helpers
 | for things such as HTTP calls, forms, and form validation errors.
 |
 | Next, we'll create the root Vue application for Spark. This will start
 | the entire application and attach it to the DOM. Of course, you may
 | customize this script as you desire and load your own components.
 |
 */

require('spark-bootstrap');

require('./components/bootstrap');

require('./filters');


/**
 * Tell our API we want the results in JSON.
 */
window.axios.defaults.headers.get['accept'] = 'application/json';

/**
 * Instant search is a blazing fast search component built for Algolia
 */
import InstantSearch from 'vue-instantsearch';

Vue.use(InstantSearch);

/**
 * Quill is a rich text editor.
 *
 * Vue quill is a wrapper around it, in a component.
 *
 * Markdown shortcuts provides the ability to write markdown in it, 
 * and have it automatically be converted.
 */
 import Quill from 'quill'
 import VueQuillEditor from 'vue-quill-editor'

 import 'quill/dist/quill.core.css'
 import 'quill/dist/quill.snow.css'

 import MarkdownShortcuts from 'quill-markdown-shortcuts-for-vue-quill-editor'
 Quill.register('modules/markdownShortcuts', MarkdownShortcuts)

 Vue.use(VueQuillEditor)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo'

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    encrypted: true
});

/**
 * Mousetrap is a keypress event listener that enables an easy and fluent syntax,
 * for catching keystrokes.
 */
window.MouseTrap = require('mousetrap')

/**
 * Prototype the Bus into the Vue instances
 * this allows invoking the Bus in templates during render.
 */
Vue.prototype.$bus = window.Bus;

/**
 * Finally, create our Vue app.
 */
var app = new Vue({
    mixins: [require('spark')]
});
