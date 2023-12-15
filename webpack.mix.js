// webpack.mix.js 

const mix = require('laravel-mix')

mix
  .js('resources/js/app.js', 'public/js')
  .js('resources/js/library.js', 'public/js')
  .vue({version: 3})
  .sass('resources/css/app.scss', 'public/css', [
    // prettier-ignore
    // require('postcss-import'),
    // require('postcss-nesting'),
    // require('tailwindcss'),
  ])
  .webpackConfig(require('./webpack.config'))
  .sourceMaps()
;

if (mix.inProduction()) {
    mix.version();
}