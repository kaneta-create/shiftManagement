// vitest.config.js
import { defineConfig } from 'vitest/config';
import vue from '@vitejs/plugin-vue';
export default defineConfig({
    plugins: [vue()],
  test: {
    globals: true,
    environment: 'happy-dom', // DOM API を使用する場合
    coverage: {
      reporter: ['text', 'json', 'html']
    },
    alias: {
      '@': '/resources/js',
    },
  }
});
