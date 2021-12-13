// require('./bootstrap');
import { createApp } from 'vue'
import { createRouter, createWebHistory } from 'vue-router';
import App from './components/App.vue';

import routes from './routes.js';

const router = createRouter({
    history: createWebHistory(),
    routes: routes.routes,
});

// router.beforeEach((to, from, next) => {
//     let isLoggedIn = localStorage.getItem('isLoggedIn');
    
//     if (to.matched.some(record => record.meta.auth)) {
//         if(isLoggedIn === 'true'){
//             var request = new XMLHttpRequest()
//             request.open('GET', "api/authcheck", false);  // `false` makes the request synchronous
//             request.send();
//             if(request.status === 401){
//                 localStorage.clear()
//                 next('/login')
//             }
//             next()
//         }
//         else
//             next('/login')
//     } else {
//       next();
//     }
// });

const app = createApp({
    components: {
        App
      }
});
app.use(router);
app.mount('#app');
  