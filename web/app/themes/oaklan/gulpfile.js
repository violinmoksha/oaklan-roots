var gulp = require('gulp');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');
var rename = require('gulp-rename');
var replace = require('gulp-replace');

var paths = { watchLess: ['./style.less', './oaklan.less'], less: ['./style.less'] };

gulp.task('default', ['less']);

gulp.task('less', function(done) {
  gulp.src(paths.less)
    .pipe(less())
    .pipe(replace('/*!', '/*'))
    .pipe(gulp.dest('./'))
    .pipe(minifyCss())
    .pipe(rename({ extname: '.min.css' }))
    .pipe(gulp.dest('./'))
    .on('end', done);
});

gulp.task('watch', function() {
  gulp.watch(paths.watchLess, ['less']);
});
