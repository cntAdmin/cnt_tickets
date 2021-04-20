/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from "vue";
import vSelect from "vue-select";
import 'vue-select/dist/vue-select.css';
import { RichTextEditorPlugin } from "@syncfusion/ej2-vue-richtexteditor";

import "../../node_modules/@syncfusion/ej2-base/styles/material.css";
import "../../node_modules/@syncfusion/ej2-inputs/styles/material.css";
import "../../node_modules/@syncfusion/ej2-lists/styles/material.css";
import "../../node_modules/@syncfusion/ej2-popups/styles/material.css";
import "../../node_modules/@syncfusion/ej2-buttons/styles/material.css";
import "../../node_modules/@syncfusion/ej2-navigations/styles/material.css";
import "../../node_modules/@syncfusion/ej2-splitbuttons/styles/material.css";
import "../../node_modules/@syncfusion/ej2-vue-richtexteditor/styles/material.css";
import VueScreen from 'vue-screen';

require('./bootstrap');
const moment = require('moment')
require('moment/locale/es')

window.Vue = require('vue');

Vue.use(require('vue-moment'), {
    moment
});
Vue.use(RichTextEditorPlugin);
Vue.use(VueScreen, 'bootstrap');

Vue.component("vue-select", vSelect);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
// ? GLOBALS
Vue.component('counter', require('./components/Counter.vue').default);
Vue.component('delete-modal', require('./components/DeleteModal').default);
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('spinner', require('./components/Spinner').default);
Vue.component('navbar', require('./components/NavbarComponent').default);
Vue.component('navbar-mobile', require('./components/NavbarMobile').default);

// ? TICKETS
Vue.component('tickets', require('./components/Tickets/Tickets').default);
Vue.component('ticket-create', require('./components/Tickets/TicketCreate').default);
Vue.component('ticket-view', require('./components/Tickets/TicketView').default);
Vue.component('ticket-edit', require('./components/Tickets/TicketEdit').default);

// ? CUSTOMERS
Vue.component('customers', require('./components/Customers/Customers').default);
Vue.component('customer-create', require('./components/Customers/CustomerCreate').default);
Vue.component('customer-edit', require('./components/Customers/CustomerEdit').default);

// ? USERS
Vue.component('users', require('./components/Users/Users').default);
Vue.component('user-create', require('./components/Users/UserCreate').default);
Vue.component('user-edit', require('./components/Users/UserEdit').default);

// ? TICKET STATUSES
Vue.component('ticket-statuses', require('./components/TicketStatuses/TicketStatuses').default);
Vue.component('ticket-status-create', require('./components/TicketStatuses/TicketStatusCreate').default);
Vue.component('ticket-status-edit', require('./components/TicketStatuses/TicketStatusEdit').default);

// ? DEPARTMENTS
Vue.component('departments', require('./components/Departments/Departments').default);
Vue.component('department-create', require('./components/Departments/DepartmentCreate').default);
Vue.component('department-edit', require('./components/Departments/DepartmentEdit').default);
// ? DEPARTMENT TYPES
Vue.component('department-types', require('./components/DepartmentTypes/DepartmentTypes').default);
Vue.component('department-type-create', require('./components/DepartmentTypes/DepartmentTypeCreate').default);
Vue.component('department-type-edit', require('./components/DepartmentTypes/DepartmentTypeEdit').default);

// ? ORIGIN TYPES
Vue.component('origin-types', require('./components/OriginTypes/OriginTypes').default);
Vue.component('origin-type-create', require('./components/OriginTypes/OriginTypeCreate').default);
Vue.component('origin-type-edit', require('./components/OriginTypes/OriginTypeEdit').default);

// ? ORIGIN TYPES
Vue.component('profile', require('./components/Profiles/Profile').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
