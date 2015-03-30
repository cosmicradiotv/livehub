var gulp = require('gulp');

gulp.task('watch', ['browserSync'], function () {
	var sass = require('../config/sass'),
		watch = require('gulp-watch');

	watch(sass.src, function () {
		gulp.start('sass');
	});
});
