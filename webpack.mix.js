const mix = require('laravel-mix');
const VuetifyLoaderPlugin = require('vuetify-loader/lib/plugin');
const CaseSensitivePathsPlugin = require('case-sensitive-paths-webpack-plugin');
//const Mdi = require('@mdi/font/css');

 var webpackConfig = {
     plugins: [
         new CaseSensitivePathsPlugin(),
         new VuetifyLoaderPlugin(),
  //       new Mdi()
     ]
 }

 mix.webpackConfig( webpackConfig );
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

mix.styles([
      'public/css/grid.min.css',
      'public/css/main.css',
      ], 'public/css/all.css');
