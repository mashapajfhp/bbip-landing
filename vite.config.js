import { defineConfig } from 'vite'

export default defineConfig({
  build: {
    outDir: 'public/build',
    emptyOutDir: true,
    manifest: 'manifest.json',
    rollupOptions: {
      input: 'resources/js/app.js'
    }
  }
})
