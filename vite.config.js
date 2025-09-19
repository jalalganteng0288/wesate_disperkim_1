// vite.config.js

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

export default defineConfig({
    plugins: [
        laravel({
            // UBAH BAGIAN INI DARI STRING MENJADI ARRAY
            input: [
                'resources/css/app.css', 
                'resources/js/app.jsx', // Ini untuk Inertia/React (login, register)
                'resources/js/admin.js'  // <-- TAMBAHKAN INI (Untuk Admin Panel Blade)
            ],
            refresh: true,
        }),
        react(),
    ],
});