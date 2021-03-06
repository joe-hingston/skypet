/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from './routes';
import VueResource from 'vue-resource';
import { TableComponent, TableColumn } from 'vue-table-component';
import Vuetify from 'vuetify';
import Card from 'vuetify-material-dashboard';

Vue.component('table-component', TableComponent);
Vue.component('table-column', TableColumn);

Vue.use(VueResource);
Vue.use(VueRouter);
Vue.use(Vuetify);
Vue.use(Card);


const app = new Vue({
    el: '#app',
    router: new VueRouter(routes),


});

