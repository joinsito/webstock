
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


$( document ).ready(function() {
    console.log($.fn.tooltip.Constructor.VERSION);
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


Vue.component('welcome', require('./components/Welcome.vue'));
Vue.component('signupweb', require('./components/Signupweb.vue'));
Vue.component('signupwebsuccess', require('./components/Signupwebsuccess.vue'));
Vue.component('siteinfo', require('./components/Siteinfo.vue'));
Vue.component('pagination', require('vue-laravel-pagination'));
Vue.component('mycarousel', require('./components/Carousel.vue'));
Vue.component('pulse-loader', require('vue-spinner/src/PulseLoader.vue'));


Vue.use(VueValidate);
Vue.use(VueHighcharts);
Vue.use(VueRouter);



const app = new Vue({
    el: '#app',
    data:
        {
            currentView: 'welcome',
            loading: false
        }
});
