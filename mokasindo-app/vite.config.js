import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js',
                    'resources/js/owner-overview.js','resources/js/owner-reports-utils.js', 
                    'resources/js/owner-reports-chartstable.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
