var gulp = require('gulp');

// 4) Rev and compress CSS and JS files (this is done after assets, so that if a
//    referenced asset hash changes, the parent hash will change as well
gulp.task('rev-css', ['rev-update-references'], function () {
	var config = require('../../config'),
		minify = require('gulp-minify-css'),
		rev = require('gulp-rev');

	return gulp.src(config.publicDirectory + '/**/*.css')
		.pipe(rev())
		.pipe(minify())
		.pipe(gulp.dest(config.publicDirectory))
		.pipe(rev.manifest('public/rev-manifest.json', {merge: true}))
		.pipe(gulp.dest(''));
});
