/*
 |--------------------------------------------------------------------------
 | Asset management via gulp
 |--------------------------------------------------------------------------
 | @Author: Raghu Chaudhary
 | @Date  : 2017-Dec-14
 |
 */

var gulp = require('gulp');

/*
 |--------------------------------------------------------------------------
 | Define source and destination file paths
 |--------------------------------------------------------------------------
 */
var bower_components = "bower_components/",
    vendor = "public/vendor/",
    src = "public/resources/",
    dest = "public/build/",
    sassOptions = {
        errLogToConsole: true,
        outputStyle: 'expanded'
    };

/*
 |--------------------------------------------------------------------------
 | Include plugins
 |--------------------------------------------------------------------------
 */
var concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache'),
    minifyCSS = require('gulp-minify-css'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    notify = require('gulp-notify'),
    browserify = require('gulp-browserify');


/*
 |--------------------------------------------------------------------------
 | Bower Components
 |--------------------------------------------------------------------------
 */
gulp.task('sweetalert', function () {
    return gulp.src(bower_components + 'sweetalert/src/**')
        .pipe(gulp.dest(vendor + 'sweetalert'));
});


/*
 |--------------------------------------------------------------------------
 | Concate and minify Javascripts
 |--------------------------------------------------------------------------
 */
gulp.task('scripts', function () {

    return gulp.src([src + 'plugins/js/**/*.js', src + 'backend/js/**/*.js', src + 'app/**/*.js'])
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(concat('app.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(dest + 'js'))
        .pipe(notify({
            title: 'Gulp',
            subtitle: 'success',
            message: 'Script successfully minified.',
            sound: "Pop"
        }));
});


/*
 |--------------------------------------------------------------------------
 | Concate and minify Css
 |--------------------------------------------------------------------------
 */
gulp.task('styles', function () {
    return gulp
        .src(src + 'backend/css/*.css')
        .pipe(sourcemaps.init())
        .pipe(autoprefixer())
        .pipe(concat('app.css'))
        .pipe(rename({suffix: '.min'}))
        .pipe(minifyCSS({keepBreaks: true}))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(dest + 'css'))
        .pipe(notify({
            title: 'Gulp',
            subtitle: 'success',
            message: 'Styles successfully minified.',
            sound: "Pop"
        }));
});

/*
 |--------------------------------------------------------------------------
 | Concate and minify Sass
 |--------------------------------------------------------------------------
 */
gulp.task('sass', function () {

    var onError = function (err) {
        notify.onError({
            title: "Gulp",
            subtitle: "Failure!",
            message: "Error: <%= error.message %>",
            sound: "Beep"
        })(err);

        this.emit('end');
    };

    return gulp
        .src(src + "sass/*.scss")
        .pipe(concat('app.scss'))
        .pipe(rename({suffix: '.min'}))
        .pipe(sass(sassOptions).on('error', sass.logError))
        .pipe(gulp.dest(dest + 'css'))
        .pipe(notify({ // Add gulpif here
            title: 'Gulp',
            subtitle: 'success',
            message: 'Sass successfully minified.',
            sound: "Pop"
        }));

});

/*
 |--------------------------------------------------------------------------
 | Compress and cache Images
 |--------------------------------------------------------------------------
 */
gulp.task('images', function () {
    return gulp.src(src + 'backend/images/**/*')
        .pipe(cache(imagemin({optimizationLevel: 5, progressive: true, interlaced: true})))
        .pipe(gulp.dest(dest + 'images'))
        .pipe(notify({
            title: 'Gulp',
            subtitle: 'success',
            message: 'Images successfully minified.',
            sound: "Pop"
        }));
});

/*
 |--------------------------------------------------------------------------
 | Watch for changes in file
 |--------------------------------------------------------------------------
 */
gulp.task('watch', function () {

    // Watch Font awsome
    gulp.watch(bower_components + 'sweetalert/src/**', ['sweetalert']);

    // Watch .js files
    gulp.watch(src + 'app/**/*.js', ['scripts']);

    // Watch .css files
    gulp.watch(src + 'backend/css/**/*.css', ['styles']);

    // Watch .scss files
    gulp.watch(src + 'sass/*.scss', ['sass']);

    // Watch image files
    gulp.watch(src + 'images/**/*', ['images']);

});


/*
 |--------------------------------------------------------------------------
 | Default tasks
 |--------------------------------------------------------------------------
 */
gulp.task('default', [
    'scripts',
    'styles',
    'sass',
    'images',
    'watch',
    'sweetalert'
]);
