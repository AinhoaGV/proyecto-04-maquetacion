import { defineConfig } from 'vite'

// Dev server sends /App requests to the PHP server
export default defineConfig({
  server: {
    port: 3000,
    proxy: {
      '/App': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/index.php': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      '/gracias.php': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },
})
