var elixir = require('laravel-elixir');
var uglify = require('gulp-uglify');
var gulp = require('gulp');
var concat = require('gulp-concat');
var runSequence = require('run-sequence');
var gulpFilter = require('gulp-filter');
var mainBowerFiles = require('gulp-main-bower-files');
var cssmin = require('gulp-cssmin');
var usemin = require('gulp-usemin');
var sourcemaps = require('gulp-sourcemaps');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */
/*
elixir(function(mix) {
    mix.sass('app.scss');
});
*/

gulp.task('default', function(){
    runSequence('compress-app-js', 'compress-vendor-js', 'compress-app-css', 'compress-vendor-css');
});

gulp.task('usemin', function(){
    return gulp.src('resources/views/main.php')
        .pipe(usemin({
            assetsDir: 'public',
            css: [ cssmin, 'concat' ],
            js: [ sourcemaps.init(), 'concat', uglify({preserveComments: 'all'}), sourcemaps.write('.')]
        }))
        .pipe(gulp.dest('dist'));
});

gulp.task('compress-app-js', function (){
    return gulp.src(
        ['public/assets/js/app.js', 'public/assets/js/**/*.js', '!public/assets/js/**/*.min.js'])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/js'));
});

gulp.task('compress-app-css', function (){
    return gulp.src(['public/assets/css/**/*.css', '!public/assets/css/**/*.min.css'])
        .pipe(concat('styles.min.css'))
        .pipe(cssmin())
        .pipe(gulp.dest('public/assets/css'));
});

gulp.task('compress-vendor-js', function (){
    return gulp.src([
        'bower_components/jquery/dist/jquery.min.js', 
        'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js', 
        ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/assets/js'));
    
    //var jsFilter = gulpFilter('**/*.js');
    //return gulp.src('./bower.json')
    //    .pipe(mainBowerFiles())
    //    .pipe(jsFilter)
    //    .pipe(concat('vendors.min.js'))
    //    .pipe(uglify())
    //    .pipe(gulp.dest('public/assets/js'));
});

gulp.task('compress-vendor-css', function (){
    var cssFilter = gulpFilter('**/*.css');
    return gulp.src('./bower.json')
        .pipe(mainBowerFiles())
        .pipe(cssFilter)
        .pipe(concat('vendors.min.css'))
        .pipe(cssmin())
        .pipe(gulp.dest('public/assets/css'));
});