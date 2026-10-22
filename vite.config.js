import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js',

                // Form Survei Publik
                'resources/css/survey-public.css',
                'resources/js/survey-public.js',
            ],
            refresh: true,
        }),
    ],
});
