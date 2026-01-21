import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/site.css', // Твой CSS
                'resources/js/app.js',
                'resources/js/site.js',   // Твой JS
            ],
            refresh: true,
        }),
    ],
});
