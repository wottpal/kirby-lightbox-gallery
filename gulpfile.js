"use strict";

/****************/
/* Load Plugins */

const gulp = require('gulp')
const util = require('gulp-util')
const rename = require('gulp-rename')
const sass = require('gulp-sass')
const autoprefixer = require('gulp-autoprefixer')
const cleancss = require('gulp-clean-css')
const uglify = require('gulp-uglify')
const babel = require('gulp-babel')


/*************/
/* Constants */

const src = './src_assets/'
const dist = './assets/'


/**********/
/* Tasks */

gulp.task('styles', () => {
  return gulp.src(src + '**/*.scss')
  .pipe(sass().on('error', util.log))
  .pipe(autoprefixer({
    browsers: ['> 1%', 'IE 9']
  }).on('error', util.log))
  .pipe(cleancss().on('error', util.log))
  .pipe(rename({extname: ".min.css"}))
  .pipe(gulp.dest(dist))
});

gulp.task('scripts', () => {
  return gulp.src(src + '**/*.js')
  .pipe(babel({
    "presets": ['es2015']
  }).on('error', util.log))
  .pipe(uglify({
    output: {
      comments: /^!|@preserve|@license|@cc_on/i
    }
  }).on('error', util.log))
  .pipe(rename({extname: ".min.js"}))
  .pipe(gulp.dest(dist))
});


/**************************/
/* Watcher & Default Task */

gulp.task('watch', () => {
  gulp.watch(src).on('change', gulp.parallel('styles', 'scripts'));
})

gulp.task('default', gulp.series(gulp.parallel('styles', 'scripts'), 'watch'));
