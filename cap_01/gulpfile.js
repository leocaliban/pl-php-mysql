const gulp = require('gulp');

const rename = require('gulp-rename');
const htmlMIN = require('gulp-htmlmin');

const uglifyCSS = require('gulp-uglifycss');
const cssImport = require('gulp-cssimport');

const notify = require('gulp-notify');

// html
gulp.task('html', () => {
    return gulp.src('report.html')
        .pipe(htmlMIN({
            collapseInlineTagWhitespace: true
        }))
        .on('error', notify.onError('Erro: <%= error.message %>'))
        .pipe(gulp.dest('C:\\wamp64\\www\\php'))
        .pipe(browserSync.stream());
});

// image
gulp.task('image', () => {
    return gulp.src('*.jpg')
        .on('error', notify.onError('Erro: <%= error.message %>'))
        .pipe(gulp.dest('C:\\wamp64\\www\\php'))
        .pipe(browserSync.stream());
});

// php
gulp.task('php', () => {
    return gulp.src('report.php')
        .on('error', notify.onError('Erro: <%= error.message %>'))
        .pipe(gulp.dest('C:\\wamp64\\www\\php'))
        .pipe(browserSync.stream());
});

// css
gulp.task('css', () => {
    return gulp.src('style.css')
        .pipe(cssImport())
        .pipe(uglifyCSS({
            'maxLineLen': 80,
            'uglyComments': true
        }))
        .pipe(rename({
            extname: '.min.css'
        }))
        .pipe(gulp.dest('C:\\wamp64\\www\\php'));
});

// watch
gulp.task('watch', () => {
    gulp.watch('*.html', gulp.task('html'));
    gulp.watch('*.css', gulp.task('css'));
    gulp.watch('*.php', gulp.task('php'));
    gulp.watch('*.jpg', gulp.task('image'));
});


gulp.task('build', gulp.series(gulp.parallel('html', 'css', 'php', 'image'), gulp.parallel('watch')));