import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { existsSync, readdirSync } from 'node:fs';
import { join } from 'node:path';

function domainInputs() {
    const root = join(__dirname, 'domains');

    if (! existsSync(root)) {
        return [];
    }

    return readdirSync(root, { withFileTypes: true })
        .filter((entry) => entry.isDirectory())
        .flatMap((entry) => {
            const css = `domains/${entry.name}/resources/css/app.css`;
            const js = `domains/${entry.name}/resources/js/app.js`;

            return [css, js].filter((file) => existsSync(join(__dirname, file)));
        });
}

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                ...domainInputs(),
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
