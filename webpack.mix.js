const mix = require('laravel-mix');
require('laravel-mix-tailwind');

const tailwindcss = require('tailwindcss')

mix.js('resources/js/app.js', 'public/js')
    .version()
    .sass('resources/sass/index.sass', 'public/css/app.css')
    .options({
          processCssUrls: false,
        postCss: [ tailwindcss('./tailwind.config.js') ],


  });
