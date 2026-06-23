import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            // Prevent Vite from watching Laravel's compiled view cache on Windows.
            // These .tmp files get locked by PHP during compilation, causing EBUSY crashes.
            ignored: ['**/storage/framework/views/**'],
        },
    },
});