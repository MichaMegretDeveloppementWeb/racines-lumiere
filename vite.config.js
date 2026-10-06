import { globSync } from 'node:fs';
import { fileURLToPath } from 'node:url';
import tailwindcss from '@tailwindcss/vite';
import laravel from 'laravel-vite-plugin';
import { defineConfig } from 'vite';

// Layout globals, page entry points and component stylesheets, discovered
// so that a new page is picked up without touching this file. Component
// scripts are imported by the globals, which register them before Alpine starts.
const ENTRY_PATTERNS = [
    'resources/css/*.css',
    'resources/js/*.js',
    'resources/css/**/index.css',
    'resources/js/**/index.js',
    'resources/css/components/**/*.css',
];

const entries = globSync(ENTRY_PATTERNS)
    .map((path) => path.replaceAll('\\', '/'))
    .sort();

export default defineConfig({
    resolve: {
        alias: {
            '@livewire': fileURLToPath(new URL('./vendor/livewire/livewire/dist/livewire.esm.js', import.meta.url)),
        },
    },
    plugins: [
        laravel({
            input: entries,
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
