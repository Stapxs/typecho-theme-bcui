import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'

// https://vite.dev/config/
export default defineConfig({
    plugins: [vue()],
    server: {
        port: 5173,
    },
    build: {
        outDir: path.resolve(__dirname,
            '../themes/typecho-theme-bcui/assets'),
        emptyOutDir: true,
        rollupOptions: {
        output: {
            entryFileNames: `[name].js`,
            chunkFileNames: `[name].js`,
            assetFileNames: `[name][extname]`,
        },
        },
    },
})