var gulp        = require('gulp'),
    config      = require('../config').assets,
    browserSync = require('browser-sync');

gulp.task('assets', function () {
	return gulp.src(config.src)
		.pipe(gulp.dest(config.dest))
		.pipe(browserSync.reload({stream: true}));
});
