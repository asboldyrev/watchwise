import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';


export default defineConfig(({ command, mode }) => {
    const env = loadEnv(mode, process.cwd(), '')

    return {
        plugins: [
            laravel({
                input: [
                    'resources/js/app.js',
                    'resources/scss/style.scss',
                ],
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
        resolve: {
            alias: {
                '@app': '/resources/js',
                '@scss': '/resources/scss',
            },
        },
        server: {
            host: true,
            hmr: {
                host: env.VITE_EXTERNAL_IP || env.APP_URL.replace(/^https?\:\/\//i, ''),
            },
            watch: {
                ignored: [
                    '**/public/media/**', // Исключаем папку с загружаемыми файлами
                ],
            },
        },
    }
});
