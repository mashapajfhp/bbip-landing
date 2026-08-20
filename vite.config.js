import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'

export default defineConfig({
  plugins: [
    laravel({
      input: 'resources/js/app.js',
      refresh: true,
    }),
  ],
  server: {
    host: '0.0.0.0',
    port: 5173,
    hmr: process.env.VITE_HOST ? {
      host: process.env.VITE_HOST || 'localhost',
      port: 5173,
      protocol: 'ws',
    } : undefined,
    watch: {
      usePolling: true,
      interval: 1000,
    },
  },
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: 'manifest.json',
    rollupOptions: {
      input: 'resources/js/app.js'
    }
  }
})
