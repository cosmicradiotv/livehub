let mix = require('laravel-mix')

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

mix.js('resources/assets/js/polyfill.js', 'public/assets')
mix.js('resources/assets/js/admin.js', 'public/assets')
mix.js('resources/assets/js/live.js', 'public/assets')
mix.sass('resources/assets/sass/admin.scss', 'public/assets')
mix.sass('resources/assets/sass/live.scss', 'public/assets')

mix.webpackConfig({
	output: {
		publicPath: Mix.isUsing('hmr') ? 'http://localhost:8080/' : '/',
		chunkFilename: mix.inProduction() ? 'assets/[name].[chunkhash].app.js' : 'assets/[name].js'
	}
})
