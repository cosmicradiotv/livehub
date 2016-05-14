import path from 'path'

import ExtractTextPlugin from 'extract-text-webpack-plugin'
import ManifestPlugin from 'webpack-manifest-plugin'
import webpack from 'webpack'

export default function ({
	prod = false
} = {}) {
	const outFolder = path.resolve(__dirname, './public/assets')

	const plugins = [
		new ManifestPlugin({
			basePath: '/assets/',
			fileName: '../manifest.json'
		})
	]

	if (prod) {
		plugins.push(
			new ExtractTextPlugin('[name].[contenthash].css'),
			new webpack.DefinePlugin({
				'process.env': {
					NODE_ENV: '"production"'
				}
			}),
			new webpack.optimize.UglifyJsPlugin({
				compress: {
					warnings: false
				}
			})
		)
	} else {
		plugins.push(
			new ExtractTextPlugin('[name].css')
		)
	}

	return {
		context: path.join(__dirname, 'resources', 'assets'),

		entry: {
			admin: ['./js/admin.js', './sass/admin.scss'],
			live: ['./js/live.js', './sass/live.scss']
		},
		output: {
			filename: prod ? '[name].[chunkhash].bundle.js' : '[name].js',
			path: outFolder,
			publicPath: '/assets/',
			chunkFilename: prod ? '[name].[id].[chunkhash].js' : '[name].[id].js',
			pathInfo: !prod
		},
		module: {
			loaders: [
				{
					// Vue files
					test: /\.vue$/,
					loader: 'vue'
				},
				{
					// JS
					test: /\.js$/,
					exclude: /node_modules/,
					loader: 'babel'
				},
				{
					// Provide foundation with jQuery
					test: path.join(__dirname, 'node_modules', 'foundation-sites'),
					exclude: /\.scss$/,
					loaders: [
						{
							loader: 'imports',
							query: {
								jQuery: 'jquery',
								define: '>false'
							}
						}
					]
				},
				{
					// Sass
					test: /\.scss$/,
					// Disable sass minification so css-loader handles it
					loader: ExtractTextPlugin.extract(['css', 'sass?outputStyle=nested'])
				},
				{
					// JaaaaSON
					test: /\.json$/,
					loader: 'json'
				},
				{
					// Images and other shenaniganiganidingdongs
					test: /\.(png|jpe?g|gif|svg|woff2?|eot|ttf|otf)(\?.*)?$/,
					loader: 'url',
					query: {
						limit: 10000,
						name: prod ? '[name].[hash:7].[ext]' : '[name].[ext]'
					}
				}
			]
		},
		babel: {
			presets: ['es2015-webpack'],
			plugins: ['transform-runtime']
		},
		debug: !prod,
		devtool: prod ? false : '#cheap-module-source-map',
		plugins
	}
}
