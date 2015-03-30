var gulp = require('gulp');

gulp.task('sass', function () {
	var browserSync = require('browser-sync'),
		sass = require('gulp-sass'),
		sourcemaps = require('gulp-sourcemaps'),
		handleErrors = require('../lib/handleErrors'),
		config = require('../config/sass'),
		autoprefixer = require('gulp-autoprefixer'),
		path = require('path'),
		through = require('through2');

	return gulp.src(config.src)
		.pipe(sourcemaps.init())
		.pipe(sass(config.settings))
		.on('error', handleErrors)
		// Fix sourcemaps so the paths won't go ../
		.pipe(through.obj(function (file, enc, callback) {
			var transformPath = function (source) {
				var abs = path.resolve(path.dirname(file.path), source);
				return path.relative(config.sourceMapBase, abs);
			};

			if(file.sourceMap) {
				if(file.sourceMap.sources) {
					file.sourceMap.sources = file.sourceMap.sources.map(transformPath);
				}
				file.sourceMap.sourceRoot = config.sourceMapBase;
			}

			this.push(file);
			callback();
		}))
		// End fix
		.pipe(sourcemaps.write())
		.pipe(sourcemaps.init({loadMaps: true}))
		.pipe(autoprefixer(config.autoprefixer))
		.pipe(sourcemaps.write('.'))
		.pipe(gulp.dest(config.dest))
		.pipe(browserSync.reload({stream: true}));
});
