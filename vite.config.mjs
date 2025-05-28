    import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    server: {
        host: "computer-gear.com", // Use your custom domain
        port: 5173, // Default Vite port
        strictPort: true,
        cors: true,
        hmr: {
            host: "computer-gear.com", // HMR host must match your domain
        },
    },
    plugins: [
        laravel({
            input: ["resources/sass/app.scss", "resources/js/app.js",'resources/js/realtime.js'],
            
            refresh: true,
        }),
    ],
});
