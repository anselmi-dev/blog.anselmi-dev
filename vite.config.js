import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    darkMode: 'class',
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    css: {
        preprocessorOptions: {
            scss: {
                // Example: Define global SCSS variables accessible throughout your project
                // additionalData: `@import "@/styles/_variables.scss";`,
                // Example: Specify the SCSS implementation if needed (usually defaults to 'sass')
                // implementation: require('sass'),
            },
        },
    },
    server: {
        cors: true,
    },
});
