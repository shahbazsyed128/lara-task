/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router'
import moment from 'moment';
import Form from 'vform'
import {  Button,
    HasError,
    AlertError,
    AlertErrors,
    AlertSuccess } from  'vform/src/components/bootstrap4';

window.Form = Form;

Vue.component(Button.name, Button)
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
Vue.component(AlertErrors.name, AlertErrors)
Vue.component(AlertSuccess.name, AlertSuccess)

Vue.use(VueRouter)

Vue.component('pagination', require('laravel-vue-pagination'));

import VueProgressBar from 'vue-progressbar';
import Vue from 'vue';

const options = {
  color: '#1ed36d',
  failedColor: '#FF0000',
  thickness: '3px',
  transition: {
    speed: '0.5s',
    opacity: '0.6s',
    termination: 300
  },
  autoRevert: true,
  location: 'top',
  inverse: false
}

Vue.use(VueProgressBar, options)

import Swal from 'sweetalert2';
window.Swal = Swal;

const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

window.Toast = Toast;
  


let routes = [
    { path: '/dashboard', component: require('./components/Dashboard.vue').default},
    { path: '/companies', component: require('./components/Companies.vue').default},
    { path: '/company_view', name: 'company-view', props: true, component: require('./components/CompanyView.vue').default },
    { path: '/employee_view', name: 'employee-view', props: true, component: require('./components/EmployeeView.vue').default },
    { path: '/employees', component: require('./components/Employees.vue').default},
    { path: '/profile', component: require('./components/Profile.vue').default }
  ]
  

const router = new VueRouter({
    mode:'history',
    routes // short for `routes: routes`
})

window.Fire = new Vue();

Vue.filter('capitalize', function (value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
  });
  
Vue.filter('myDate',function(date){
     return moment(date).fromNow();
  });
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

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



const app = new Vue({
    el: '#app',
    router
});

