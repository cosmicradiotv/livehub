var gulp = require('gulp');

gulp.task('watch', ['browserSync'], function () {
	var sass = require('../config/sass'),
		watchConfig = require('../config').watch,
		watch = require('gulp-watch');

	watch(sass.src, watchConfig, function () {
		gulp.start('sass');
	});
});
