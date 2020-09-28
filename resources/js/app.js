/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
require('./plugins/fontawesome');

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

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('inventario', require('./view/Inventario.vue').default);
Vue.component('ventas', require('./view/Ventas.vue').default);
Vue.component('despachos', require('./view/Despachos.vue').default);
Vue.component('despachos-almacen', require('./view/Despachos-almacen.vue').default);
Vue.component('compras', require('./view/Compras.vue').default);
Vue.component('index', require('./view/Index.vue').default);
Vue.component('home', require('./view/Home.vue').default);

Vue.component('venta-create', require('./view/VentaCreate.vue').default);
Vue.component('compra-create', require('./view/CompraCreate.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
import Vue from 'vue'
import {ModalPlugin, PaginationPlugin, AlertPlugin } from 'bootstrap-vue'

//Vue.component('b-modal', BModal)
// Note that Vue automatically prefixes directive names with `v-`
//Vue.directive('b-modal', VBModal)

//Vue.component('b-pagination', BPagination)
// Install BootstrapVue
//Vue.use(BootstrapVue)
Vue.use(ModalPlugin)
Vue.use(PaginationPlugin)
Vue.use(AlertPlugin)
// Optionally install the BootstrapVue icon components plugin
//Vue.use(IconsPlugin)

//import 'bootstrap/dist/css/bootstrap.css'
//import 'bootstrap-vue/dist/bootstrap-vue.css'


const app = new Vue({
    el: '#app',
});
