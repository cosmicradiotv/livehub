var gulp         = require('gulp'),
    browserSync  = require('browser-sync'),
    sass         = require('gulp-sass'),
    sourcemaps   = require('gulp-sourcemaps'),
    handleErrors = require('../util/handleErrors'),
    config       = require('../config').sass,
    autoprefixer = require('gulp-autoprefixer');

gulp.task('sass', function () {
	return gulp.src(config.src)
		.pipe(sourcemaps.init({debug:true}))
		.pipe(sass(config.settings))
		.on('error', handleErrors)
		.pipe(autoprefixer({browsers: ['last 2 version']}))
		.pipe(sourcemaps.write())
		.pipe(gulp.dest(config.dest))
		.pipe(browserSync.reload({stream: true}));
});
