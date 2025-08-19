import { createApp } from 'vue';
import { createPinia } from 'pinia';
import router from './router';
import App from './components/App.vue';

createApp(App).use(createPinia()).use(router).mount('#app');
