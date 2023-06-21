import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: {
        app: 'resources/js/app.js',
        dashboard: [
          'resources/js/dashboard.js',
          'resources/js/dashboardChart.js',
          'resources/js/dashboardDatatables.js'
        ]
      },
      refresh: true,
    }),
  ],
});