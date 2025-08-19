import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    plugins: [
        laravel({ input: ['resources/js/app.js'], refresh: true }),
        vue(),
    ],
    server: {
        host: true,
        port: 5173,
        hmr: { host: '127.0.0.1', protocol: 'ws', port: 5173 },
        origin: 'http://127.0.0.1:5173',
        cors: { origin: ['http://127.0.0.1:7080','http://localhost:7080'] },
    },
});
