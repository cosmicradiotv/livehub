var gulp = require('gulp');

gulp.task('webpack:development', function (callback) {
	var built = false,
		config = require('../config/webpack')('development'),
		watchConfig = require('../config').watch,
		logger = require('../lib/compileLogger'),
		webpack = require('webpack'),
		browserSync = require('browser-sync');

	/**
	 * Fix for when running in VM:
	 * @see https://github.com/substack/watchify/issues/162#issuecomment-85619780
	 */
	if(watchConfig.usePolling) {
		var chokidar = require('webpack/node_modules/watchpack/node_modules/chokidar');
		var origWatch = chokidar.watch;

		chokidar.watch = function(file, opts) {
			// @see https://github.com/paulmillr/chokidar#performance
			opts.usePolling = true;
			opts.interval = 500;
			return origWatch.call(chokidar, file, opts);
		};

	}


	webpack(config).watch(200, function (err, stats) {
		logger(err, stats);
		browserSync.reload();
		// On the initial compile, let gulp know the task is done
		if (!built) {
			built = true;
			callback()
		}
	})
});
