var gulp = require('gulp');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var minifyCss = require('gulp-minify-css');

gulp.task( 'prod', function() {
	gulp.src('assets/js/*.js')
		.pipe(uglify())
		.pipe(rename({
			extname: '.min.js'
		}))
		.pipe(gulp.dest('build/js'));
	
	gulp.src('assets/css/*.css')
		.pipe(minifyCss({compatiability:'ie8'}))
		.pipe(rename({
			extname: '.min.css'
		}))
		.pipe(gulp.dest('build/css/min'))
		
});


gulp.task( 'dev', function() {
	gulp.src('assets/scss/*.scss')
		.pipe(sass().on('error', sass.logError))
		.pipe(gulp.dest('assets/css'))
		
});

gulp.task('default', ['dev']);