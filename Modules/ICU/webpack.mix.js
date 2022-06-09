const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/icu.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/icu.css');

if (mix.inProduction()) {
    mix.version();
}