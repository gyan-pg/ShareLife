const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/style.scss', 'public/css')
   .browserSync({
    proxy:{
      target: "http://127.0.0.1:8000",
    },
    files:[
      'resources/views/**/*.blade.php',
      'resources/views/*.blade.php',
      'public/css/style.css',
      'public/js/app.js'
    ]
  })
  .version();