const mix = require('laravel-mix');
const path = require('path');

mix.js('resources/js/app.js', 'public/js')
    .vue()
    .sourceMaps()
    .alias({
        '@': path.resolve('resources/js'),
        // ziggy: path.resolve('vendor/tightenco/ziggy/dist/vue'),
    });


//     .postCss('resources/css/app.css', 'public/css', [
//     require('postcss-import'),
//     require('tailwindcss'),
//     require('autoprefixer'),
// ]);

if (mix.inProduction()) {
    mix.version();
}
