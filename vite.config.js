import vue from "@vitejs/plugin-vue";
import laravel from "laravel-vite-plugin";
import { defineConfig } from "vite";

export default defineConfig({
  server: {
    watch: {
      ignored: ['**/.env'], // Ignore .env file changes
    },
  },
  build:{
    chunkSizeWarningLimit: 2048,
  },
  plugins: [
    laravel({
      input: ["resources/sass/app.scss", "resources/js/app.js"],
      refresh: true,
    }),
    vue(),
  ],
});
