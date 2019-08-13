import index from './pages/index';
import login from './pages/login';
import clientParams from './pages/clientParams';
import clientPatternLinks from './pages/clientPatternLinks';
import clientLinks from './pages/clientLinks';
import clientSetting from './pages/clientSetting';
import addClient from './pages/addClient';

export const routes = [
    {
        path: '/',
        component: index,
        name: 'index',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/login',
        component: login,
        name: 'login'
    },
    {
        path: '/client/new',
        component: addClient,
        name: 'addClient',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/client/:name/params',
        component: clientParams,
        name: 'clientParams',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/client/:name/patterns',
        component: clientPatternLinks,
        name: 'clientPatternLinks',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/client/:name/setting',
        component: clientSetting,
        name: 'clientSetting',
        meta: {
            requiresAuth: true
        }
    },
    {
        path: '/client/:name/links',
        component: clientLinks,
        name: 'clientLinks',
        meta: {
            requiresAuth: true
        }
    }
];