import AllProduct from '../components/views/Product.vue';

//auth form
import Login from '../components/views/auth/Login.vue';
import Register from '../components/views/auth/Register.vue';

export const routes = [
    {
        name: 'register',
        path: '/register',
        component: Register,
        meta: {
            hideForAuth: true
        }
    },
    {
        name: 'login',
        path: '/login',
        component: Login,
        meta: {
            hideForAuth: true
        }
    },
    {
        name: 'home',
        path: '/',
        component: AllProduct,
        meta: { requiresAuth: true }
    },
    {
        path:'*',
        component: Login,
    }
];




