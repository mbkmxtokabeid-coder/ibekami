import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    build: {
        // Minify dengan esbuild (default, lebih cepat dari terser)
        minify: 'esbuild',
        // Pisah vendor chunk agar browser bisa cache lebih efisien
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios'],
                },
            },
        },
        // Inline aset kecil (<4KB) langsung ke JS/CSS — kurangi HTTP request
        assetsInlineLimit: 4096,
        // CSS code splitting
        cssCodeSplit: true,
    },
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
