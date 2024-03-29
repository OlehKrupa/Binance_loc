const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
.js('resources/js/dashboard.js','public/js')
.js('resources/js/preferences.js','public/js')
.sass('resources/sass/app.scss', 'public/css')
.css('resources/css/dashboard.css','public/css')
.css('resources/css/preferences.css','public/css')