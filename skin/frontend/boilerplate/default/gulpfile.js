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

//var cssflip =        require('gulp-css-flip');

var bless = require('gulp-bless');

gulp.task('less', function() {
    return gulp.src('src/less/style.less')
        .pipe(less())
        //.pipe(minifycss())

        //.pipe(bless())
        .pipe(gulp.dest('dist/css'))

        // UNCOMMENT TO CREATE RTL CSS FILES
        //.pipe(rename({suffix: '-rtl'}))
        //.pipe(rename({suffix: '-rtl'}))
        //.pipe(cssflip.gulp())
        //.pipe(gulp.dest('dist/css'))

        .pipe(livereload(server))
        .pipe(notify({
            message: 'Successfully compiled LESS'
        }));
});

gulp.task('lint', function() {
    return gulp.src('src/js/script.js')
        .pipe(jshint())
        .pipe(jshint.reporter('default'))
        .pipe(livereload(server));
});

gulp.task('js.libs', function() {
    return gulp.src([

            'bower_components/jquery/dist/jquery.js',

            'bower_components/imagesloaded/imagesloaded.pkgd.min.js',

            // jQuery UI
            'bower_components/jquery-ui/ui/core.js',
            'bower_components/jquery-ui/ui/widget.min.js',
            //'bower_components/jquery-ui/ui/minified/spinner.min.js'

            // jQuery Magnific Popup
            'bower_components/magnific-popup/dist/jquery.magnific-popup.js',

            // Bootstrap
            'bower_components/bootstrap/js/dropdown.js',

            // jQuery Cycle 2
            'bower_components/jquery-cycle2/build/jquery.cycle2.js',
            'bower_components/jquery-cycle2/build/plugin/jquery.cycle2.carousel.js',
            'bower_components/jquery-cycle2/build/plugin/jquery.cycle2.swipe.js',

            // enquire JS
            'bower_components/enquire/dist/enquire.js',

            // SPIN JS
            // https://github.com/fgnass/spin.js
            'bower_components/spinjs/spin.js',
            'bower_components/spinjs/jquery.spin.js'

            // #######################################################
            // COMMON js files libraries.
            // don't forget to uncomment their respective bower.json.
            // ########################################################

            //'static/sidr-master/dist/jquery.sidr.js'
            //'bower_components/select2/select2.min.js',
            //'bower_components/blockui/jquery.blockUI.js',
            //'bower_components/qtip2/jquery.qtip.js',
            //'static/jquery.ui.scrollbar/js/jquery.mousewheel.js',
            //'static/jquery.ui.scrollbar/js/jquery.ui.scrollbar.js',
            //jquery ui slider support mobile touch
            //'static/jquery-ui-touch-punch/jquery.ui.touch-punch.js',
            //'static/sticky-kit/jquery.sticky-kit.min.js'

        ])
        .pipe(concat('libs.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('../../../../js/boilerplate'))
        .pipe(livereload(server))
        .pipe(notify({
            message: 'Successfully compiled JS lib boilerplate'
        }));


});


/*gulp.task('instagram', function() {
    return gulp.src([
            'bower_components/packery/dist/packery.pkgd.min.js',
            'static/instagram/jquery-instagram.js',
            'static/jsrender-master/jsrender.js'
        ])
        .pipe(concat('instagram.js'))
        //.pipe(uglify())
        .pipe(gulp.dest('../../../../js/boilerplate'))
        .pipe(livereload(server))
        .pipe(notify({
            message: 'Successfully compiled Instagram JS lib boilerplate'
        }));


});*/

gulp.task('js', function() {
    return gulp.src([
            'src/js/plugins.js',
            'src/js/script.js'
        ])
        .pipe(concat('script.js'))
        //.pipe(uglify())
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