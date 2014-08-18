var gulp = require('gulp');

var less      = require('gulp-less');
var minifycss = require('gulp-minify-css');

var jshint = require('gulp-jshint');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');

var notify     = require('gulp-notify');
var clean      = require('gulp-clean');
var livereload = require('gulp-livereload');
var lr         = require('tiny-lr');
var server     = lr();
var rename     = require('gulp-rename');
var cssflip =        require('gulp-css-flip');

gulp.task('less', function() {
    return gulp.src('src/less/style.less')
        .pipe(less())
        //.pipe(minifycss())
        .pipe(gulp.dest('dist/css'))
        //.pipe(rename({suffix: '-rtl'}))
        //.pipe(cssflip.gulp())
        //.pipe(gulp.dest('dist/css'))
        //.pipe(livereload(server))
        /*.pipe(notify({
            message: 'Successfully compiled LESS'
        }))*/;
});

gulp.task('lint', function() {
    return gulp.src('src/js/script.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(livereload(server));
});

gulp.task('js', function() {
    return gulp.src([
            'bower_components/imagesloaded/imagesloaded.pkgd.min.js',
            'bower_components/jquery-cycle2/build/jquery.cycle2.min.js',
            'bower_components/jquery-ui/ui/minified/core.min.js',
            'bower_components/jquery-ui/ui/minified/widget.min.js',
            'bower_components/magnific-popup/dist/jquery.magnific-popup.min.js',
            'bower_components/bootstrap/js/dropdown.js',
            'src/js/script.js'
        ])
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(gulp.dest('dist/js'))
        .pipe(livereload(server))
        .pipe(notify({
            message: 'Successfully compiled JS'
        }));
});

// Copy all static images
var imagePaths = [
    'src/img/**/*'
];
gulp.task('img', function () {
    return gulp.src(imagePaths)
        // Pass in options to the task
        //.pipe(imagemin({optimizationLevel: 5}))
        .pipe(gulp.dest('dist/img'));
});

// Clean
gulp.task('clean', function() {
    return gulp.src(['dist/css', 'dist/js'], {read: false})
        .pipe(clean());
});

// Default task
gulp.task('default', ['clean'], function() {
    gulp.start('less', 'lint', 'js');
});

// Watch
gulp.task('watch', function() {
    // Listen on port 35729
    server.listen(35729, function (err) {
        if (err) {
            return console.log(err);
        }

        // Watch .less files
        gulp.watch('src/less/**/*.less', ['less']);

        // Watch .js files
        gulp.watch('src/js/**/*.js', ['lint', 'js']);
    });
});