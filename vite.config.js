import { defineConfig } from 'vite'
import { resolve } from 'path'

// Dev server sends /App requests to the PHP server
export default defineConfig(({ command }) => ({
  base: command === 'build' ? './' : '/',
  server: {
    port: 3000,
    proxy: {
      '/App': {
        // Backend PHP (XAMPP o php -S); ajusta el puerto si usas otro
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
      // Redirección a /index.php debe pasar por PHP, no servirse en estático por Vite
      '/index.php': {
        target: 'http://localhost:8000',
        changeOrigin: true,
      },
    },
  },

  
  build: {
    // Genera manifest.json para poder cargar los assets compilados desde PHP
    // Al poner string, el manifest se guarda en la raiz de /dist (no en /dist/.vite)
    manifest: 'manifest.json',
    // Como ya no existe index.html, usamos una entrada JS explicita
    // (Vite normalmente busca index.html como punto de entrada)
    rollupOptions: {
      input: {
        app: resolve(__dirname, 'src/main.js'),
      },
    },
  },
}))