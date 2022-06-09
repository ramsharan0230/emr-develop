/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

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

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('fiscal-year-dashboard', require('./components/dashboard/FiscalYear.vue').default);
Vue.component('in-out-emergency', require('./components/dashboard/inOutEmergency.vue').default);
Vue.component('new-old-follow-up', require('./components/dashboard/PatientCount.vue').default);
Vue.component('online-walking', require('./components/dashboard/OnlineWalking.vue').default);
Vue.component('ot-count', require('./components/dashboard/OtCount.vue').default);
Vue.component('delivery-count', require('./components/dashboard/delivery-count.vue').default);
Vue.component('pharmacy-count', require('./components/dashboard/PharmacyCount.vue').default);
Vue.component('current-inpatient', require('./components/dashboard/CurrentInpatient.vue').default);
Vue.component('death', require('./components/dashboard/Death.vue').default);
Vue.component('lab-details', require('./components/dashboard/lab-details.vue').default);
Vue.component('radio-details', require('./components/dashboard/radio-details.vue').default);
Vue.component('age-wise-details', require('./components/dashboard/age-wise-details.vue').default);
Vue.component('revenue-details', require('./components/dashboard/revenue-details.vue').default);
Vue.component('radiology-reports', require('./components/dashboard/radiology-reports.vue').default);
Vue.component('lab-report', require('./components/dashboard/lab-report.vue').default);
// Vue.component('pharmacy-details', require('./components/dashboard/pharmacy.vue').default);
Vue.component('billing-report-export-notification', require('./components/billingreport/billingdetailexport.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

$(() => {
    const app = new Vue({
        el: '#app',
    });
});
