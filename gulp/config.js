var dest = 'public/assets',
    src  = 'resources/assets',
    env  = require('node-env-file');

// Load envfile
env('./.env');

module.exports = {

	browserSync: {
		proxy: process.env.APP_URL
	},

	sass: {
		src: src + '/sass/**/*.{sass,scss}',
		dest: dest + '/css',
		settings: {
			// Required if you want to use SASS syntax
			// See https://github.com/dlmanning/gulp-sass/issues/81
			sourceComments: 'map',
			imagePath: '/assets/images' // Used by the image-url helper
		}
	},

	images: {
		src: src + '/images/**',
		dest: dest + '/images'
	},

	assets: {
		src: [
			src + '/**',
			'!' + src + '/sass{,/**}', '!' + src + '/images{,/**}', '!' + src + '/js{,/**}'
		],
		dest: dest
	},

	browserify: {
		// A separate bundle will be generated for each
		// bundle config in the list below
		bundleConfigs: [
			{
				// Live page
				entries: './' + src + '/js/boot/live.js',
				dest: dest + '/js',
				outputName: 'live.js',
				// Additional file extentions to make optional
				extensions: ['.hbs'],
				// list of modules to make require-able externally
				require: []
			}, {
				// Admin page
				entries: './' + src + '/js/boot/admin.js',
				dest: dest + '/js',
				outputName: 'admin.js',
				// Additional file extentions to make optional
				extensions: ['.hbs'],
				// list of externally available modules to exclude from the bundle
				external: []
			}, {
				// Head files
				entries: './'+src+'/js/boot/head.js',
				dest: dest + '/js',
				outputName: 'head.js'
			}
		]
	},

	production: {
		cssSrc: dest + '/css/*.css',
		jsSrc: dest + '/js/*.js',
		dest: dest
	}
};
