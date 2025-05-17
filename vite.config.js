import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';
import { resolve } from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: [
                'resources/views/**',
                'routes/**',
                'app/**',
                'lang/**'
            ],
        }),
    ],
    server: {
        https: {
            key: fs.readFileSync(resolve(__dirname, 'key.pem')),
            cert: fs.readFileSync(resolve(__dirname, 'cert.pem')),
        },
        host: 'healthcare.test',
        port: 5173,  // Explicit port declaration
        strictPort: true,  // Prevent port fallback
        hmr: {
            host: 'healthcare.test',
            protocol: 'wss'  // Required for HTTPS
        },
        cors: true,  // Enable CORS
        open: false  // Disable auto-opening browser
    },
    resolve: {
        alias: {
            '@': resolve(__dirname, './resources/js')
        }
    }
});
