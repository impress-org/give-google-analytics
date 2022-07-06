const mix = require('laravel-mix');
const WebpackRTLPlugin = require('webpack-rtl-plugin');

mix.setPublicPath('assets/dist')
    .sass('assets/src/css/give-ga-settings.scss', 'css/give-ga-settings.css')
    .js('assets/src/js/give-ga-settings.js', 'js/give-ga-settings.js')
    .sourceMaps(false, 'source-map')

    .copyDirectory('assets/src/img', 'assets/dist/img')

mix.options({
    // Don't perform any css url rewriting by default
    processCssUrls: false,

    // Prevent LICENSE files from showing up in JS builds
    terser: {
        extractComments: (astNode, comment) => false,
        terserOptions: {
            format: {
                comments: false,
            },
        },
    },
});

if (mix.inProduction()) {
    mix.webpackConfig({
        plugins: [
            new WebpackRTLPlugin({
                suffix: '-rtl',
                minify: true,
            }),
        ],
    });
}

