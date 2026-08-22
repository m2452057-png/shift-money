import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { bunny } from 'laravel-vite-plugin/fonts';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
    'resources/css/app.css',
    'resources/css/login.css',
    'resources/css/dashboard.css',
    'resources/css/shift-input.css',
    'resources/css/savegoal.css',
    'resources/js/app.js',
    'resources/js/shift-input.js',

],
            refresh: true,
            fonts: [
                bunny('Instrument Sans', {
                    weights: [400, 500, 600],
                }),
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
