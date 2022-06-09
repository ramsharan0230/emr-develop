const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');

mix.setPublicPath('../../public').mergeManifest();

mix.js(__dirname + '/Resources/assets/js/app.js', 'js/diagnosis.js')
    .sass( __dirname + '/Resources/assets/sass/app.scss', 'css/diagnosis.css');

if (mix.inProduction()) {
    mix.version();
}