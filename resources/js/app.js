/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import BootstrapVue from 'bootstrap-vue/dist/bootstrap-vue.esm';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap/dist/css/bootstrap.css';

Vue.use(BootstrapVue);

import VueAxios from 'vue-axios'
import VueSocialauth from 'vue-social-auth'

import VueResource from 'vue-resource';



/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('InfiniteLoading', require('vue-infinite-loading'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

 Vue.use(VueAxios, axios);
 Vue.use(VueSocialauth, {
     providers: {
         google: {
             clientId: '553836707820-f244ngnib2q0fs8iqgvhm9mtuloe12rf.apps.googleusercontent.com',
             client_secret: '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',
             redirectUri: 'http://localhost:8000/auth/google/callback'
         }
     }
 });

const app = new Vue({
    el: '#app',
});
