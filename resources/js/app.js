require('./bootstrap');
window.Vue = require('vue').default;

//vue-router
import VueRouter from 'vue-router';
Vue.use(VueRouter);

//routes
import { routes } from './routes';

//app(master page)
import App from './App.vue';

//fontawesome
import '@fortawesome/fontawesome-free/js/all.js';

/*iziToast*/
import iziToast  from 'izitoast';
window.iziToast = iziToast;

/*vue progress var*/
import VueProgressBar from 'vue-progressbar'
Vue.use(VueProgressBar, {
    color: 'rgb(143, 255, 199)',
    failedColor: 'red',
    height: '4px'
})


//vue pagination
Vue.component('pagination', require('laravel-vue-pagination'));

//Filter
import moment from 'moment';
Vue.filter('upperText',function (txt) {
    return txt.charAt(0).toUpperCase()+txt.slice(1);
});
Vue.filter('formateDate',function (dt) {
    return moment(dt).format('MMMM Do YYYY');
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const router = new VueRouter({
    mode: 'history',
    routes: routes
});

const app = new Vue({
    el: '#app',
    router: router,
    render: h => h(App),

});
