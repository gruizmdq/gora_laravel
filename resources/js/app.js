/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require("bootstrap-css-only/css/bootstrap.min.css");
require("mdbvue/lib/css/mdb.min.css");
require("@fortawesome/fontawesome-free/css/all.min.css");

import 'vue-select/dist/vue-select.css';
import VueNoty from 'vuejs-noty';
import vSelect from 'vue-select'

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));
Vue.use(VueNoty);
Vue.component('zone-selector', require('./components/ZoneSelector.vue').default);
Vue.component('status-selector', require('./components/StatusSelector.vue').default);
Vue.component('autocomplete',require('./components/Autocomplete.vue').default);
Vue.component('autocomplete-bis',require('./components/Autocomplete2.vue').default);
Vue.component('input-number',require('./components/InputNumber.vue').default);
Vue.component('order-row',require('./components/OrderTableRow.vue').default);
Vue.component('route-actual',require('./components/RouteActual.vue').default);
Vue.component('route-next',require('./components/RouteNext.vue').default);
Vue.component('route-component',require('./components/RouteComponent.vue').default);
Vue.component('v-select', vSelect)


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
