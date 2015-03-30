var gulp = require('gulp');

gulp.task('build:production', function(cb) {
	var gulpSequence = require('gulp-sequence');

	process.env.NODE_ENV = 'production';
	gulpSequence('clean', ['sass', 'webpack:production'], 'rev', cb);
});
