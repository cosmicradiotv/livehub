var path = require('path'),
	paths = require('./'),
	webpack = require('webpack'),
	webpackManifest = require('../lib/webpackManifest');

module.exports = function (env) {

	var jsSrc = paths.sourceAssets + '/js/';
	var jsDest = paths.publicAssets + '/js/';
	var publicPath = 'assets/js/';

	var webpackConfig = {
		entry: {
			head: [jsSrc + 'boot/head.js'],
			admin: [jsSrc + 'boot/admin.js'],
			live: [jsSrc + 'boot/live.js']
		},

		output: {
			path: jsDest,
			filename: env === 'production' ? '[name]-[hash].js' : '[name].js',
			publicPath: publicPath
		},

		plugins: [],

		resolve: {
			alias: {
				foundation: path.join(__dirname, '../../resources/assets/bower/foundation/js/foundation'),
				foundation$: path.join(__dirname, '../../resources/assets/bower/foundation/js/foundation.js'),
				modernizr$: path.join(__dirname, '../../resources/assets/bower/modernizr/modernizr.js')
			},
			extensions: ['', '.js']
		},

		module: {
			loaders: [
				{
					test: /\.js$/,
					loader: 'babel-loader?experimental',
					exclude: [/node_modules/, /resources\/assets\/bower/]
				},
				{
					test: /resources\/assets\/bower\/modernizr/,
					loader: 'imports?this=>window'
				},
				{
					test: /resources\/assets\/bower\/foundation/,
					loader: 'imports?jQuery=jQuery'
				}
			]
		}
	};

	if (env !== 'test') {
		// Factor out common dependencies into a shared.js
		webpackConfig.plugins.push(
			new webpack.optimize.CommonsChunkPlugin({
				name: 'head'
//				filename: env === 'production' ? '[name]-[hash].js' : '[name].js'
			})
		);
	}

	if (env === 'development') {
		webpackConfig.devtool = 'sourcemap';
		webpack.debug = true
	}

	if (env === 'production') {
		webpackConfig.plugins.push(
			new webpackManifest(publicPath, 'public'),
			new webpack.DefinePlugin({
				'process.env': {
					'NODE_ENV': JSON.stringify('production')
				}
			}),
			new webpack.optimize.DedupePlugin(),
			new webpack.optimize.UglifyJsPlugin()
		)
	}

	return webpackConfig
};
