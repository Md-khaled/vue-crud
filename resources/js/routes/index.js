import AllProduct from '../components/views/Product.vue';

//auth form
import Login from '../components/views/auth/Login.vue';
import Register from '../components/views/auth/Register.vue';

export const routes = [
    {
        name: 'register',
        path: '/register',
        component: Register
    },
    {
        name: 'login',
        path: '/login',
        component: Login
    },
    {
        name: 'home',
        path: '/',
        component: AllProduct
    },
];
