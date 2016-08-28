'use strict';
var gulp = require('gulp');
var minify = require('gulp-minify');
 

gulp.task('compress', function() {
  gulp.src('./*.js')
    .pipe(minify({
        ext:{
            src:'.src.js',
            min:'.min.js'
        },
        exclude: ['tasks'],
        noSource: true,
        ignoreFiles: ['.combo.js', '-min.js', 'gulpfile.js']
    }))
    .pipe(gulp.dest('dist'))
});