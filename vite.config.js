import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

// in container serverul trebuie sa asculte pe 0.0.0.0, dar browserul de pe host
// acceseaza HMR-ul pe localhost; in afara Docker-ului nu se schimba nimic.
const inDocker = process.env.DOCKER === "true";

export default defineConfig({
    server: inDocker
        ? {
              host: "0.0.0.0",
              port: 5173,
              strictPort: true,
              hmr: { host: "localhost", port: 5173 },
              watch: { usePolling: true, interval: 300 },
          }
        : undefined,
    plugins: [
        laravel({
            input: ["resources/js/app.js"],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
   optimizeDeps: {
        exclude: ["js-big-decimal"],
    },
});
