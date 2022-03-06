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


 mix.scripts(['resources/views/admin/app.js', 'resources/views/admin/**/*.js'], 'public/admin/all.js')
     .copy('resources/views/admin', 'public/admin');