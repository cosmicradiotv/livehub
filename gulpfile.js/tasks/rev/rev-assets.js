var gulp = require('gulp');

// 1) Add md5 hashes to assets referenced by CSS and JS files
gulp.task('rev-assets', function () {
	var config = require('../../config');
	var rev = require('gulp-rev');

	// See comment below about eot,woff, and ttf
	var notThese = '!' + config.publicDirectory + '/**/*+(css|js|json|eot|woff|ttf|html|php|txt|ico)';

	return gulp.src([config.publicDirectory + '/**/*', notThese])
		.pipe(rev())
		.pipe(gulp.dest(config.publicDirectory))
		.pipe(rev.manifest('public/rev-manifest.json', {merge: true}))
		.pipe(gulp.dest(''));
});
