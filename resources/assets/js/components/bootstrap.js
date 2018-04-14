
/*
 |--------------------------------------------------------------------------
 | Laravel Spark Components
 |--------------------------------------------------------------------------
 |
 | Here we will load the Spark components which makes up the core client
 | application. This is also a convenient spot for you to load all of
 | your components that you write while building your applications.
 */

require('./../spark-components/bootstrap');

require('./home');
require('./settings/profile-details');
require('./kiosk/board/manage-boards');
require('./kiosk/workout/manage-workouts');
require('./kiosk/recordings/manage-recordings');
require('./kiosk/recordings/list-recordings');
require('./kiosk/recordings/create-recording');

require('./workout');

Vue.component('search-modal', require('./search-modal.vue'));
Vue.component('photo-modal', require('./photo-modal.vue'));

require('./board/show-board');

Vue.component('thread-card', require('./thread/thread-card.vue'));
Vue.component('new-thread-card', require('./thread/new-thread-card.vue'));

require('./thread/reply/thread-replies');
require('./thread/thread-participants');

Vue.component('reply-card', require('./reply/reply-card.vue'));