import './bootstrap';

import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createPinia } from 'pinia';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';
import Login from './pages/auth/Login.vue';
import App from './App.vue';
import { useAuthUserStore } from './stores/AuthUserStore';
import { useSettingStore } from './stores/SettingStore';
import Multiselect from '@suadelabs/vue3-multiselect';
import  {Bootstrap4Pagination}  from 'laravel-vue-pagination';
import 'viewerjs/dist/viewer.css';
import VueViewer from 'v-viewer'
import moment from 'moment';


const pinia = createPinia();
const app = createApp(App);


const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});

router.beforeEach(async (to, from) => {
    const authUserStore = useAuthUserStore();
    if (authUserStore.user.name === '' && to.name !== 'admin.login') {
        const settingStore = useSettingStore();
        await Promise.all([
            authUserStore.getAuthUser(),
            settingStore.getSetting(),
        ]);
    }
});

app.component('multiselect', Multiselect)
app.component('Bootstrap4Pagination', Bootstrap4Pagination)
app.use(pinia);
app.use(router);
app.use(VueViewer);




// if (window.location.pathname === '/login') {
//     const currentApp = createApp({});
//     currentApp.component('Login', Login);
//     currentApp.mount('#login');
// } else {
//     app.mount('#app');
// }

app.mount('#app');
