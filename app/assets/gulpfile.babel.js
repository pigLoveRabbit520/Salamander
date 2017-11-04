'use strict';

import plugins  from 'gulp-load-plugins';
import yargs    from 'yargs';
import browser  from 'browser-sync';
import gulp     from 'gulp';
import panini   from 'panini';
import rimraf   from 'rimraf';
import sherpa   from 'style-sherpa';
import yaml     from 'js-yaml';
import fs       from 'fs';

// Load all Gulp plugins into one variable
const $ = plugins();

// Check for --production flag
const PRODUCTION = !!(yargs.argv.production);

// Load settings from settings.yml
const { COMPATIBILITY, PORT, UNCSS_OPTIONS, PATHS } = loadConfig();

function loadConfig() {
  let ymlFile = fs.readFileSync('config.yml', 'utf8');
  return yaml.load(ymlFile);
}

// Build the "dist" folder by running all of the below tasks
gulp.task('build.app',
  gulp.series(gulp.parallel(sass, javascript('appjs'), images, copy)));

gulp.task('build.foundation-datepicker', javascript('foundation-datepickerjs'));

// Build the site, run the server, and watch for file changes
gulp.task('default',
  gulp.series('build.app', 'build.foundation-datepicker', watch('appjs')));

gulp.task('build',
  gulp.series('build.app', 'build.foundation-datepicker'));

gulp.task('sass', sass);

// Delete the "dist" folder
// This happens every time a build starts
function clean(done) {
  rimraf(PATHS.dist, done);
}

// Copy files out of the assets folder
// This task skips over the "img", "js", and "scss" folders, which are parsed separately
function copy() {
  return gulp.src(PATHS.assets)
    .pipe(gulp.dest(PATHS.dist + '/assets'));
}

// Compile Sass into CSS
// In production, the CSS is compressed
function sass() {
  return gulp.src(['src/scss/{app,foundation-datepicker}.scss'])
    .pipe($.sourcemaps.init())
    .pipe($.sass({
      includePaths: PATHS.sass
    }).on('error', $.sass.logError))
    .pipe($.autoprefixer({
      browsers: COMPATIBILITY
    }))
    // Comment in the pipe below to run UnCSS in production
    //.pipe($.if(PRODUCTION, $.uncss(UNCSS_OPTIONS)))
    .pipe($.if(PRODUCTION, $.cssnano()))
    .pipe($.cleanCss())
    .pipe($.if(!PRODUCTION, $.sourcemaps.write()))
    .pipe($.rename({suffix: '.min'}))
    .pipe(gulp.dest(PATHS.dist + '/css'))
    .pipe(browser.reload({ stream: true }));
}

// Combine JavaScript into one file
// In production, the file is minified
function javascript(jsfile) {
  return function () {
    let jsname = jsfile.replace(/js$/, ".js");
    return gulp.src(PATHS[jsfile])
      .pipe($.sourcemaps.init())
      .pipe($.concat(jsname))
      .pipe(
          $.uglify()
              .on('error', e => { console.log(e); })
      )
      .pipe($.if(!PRODUCTION, $.sourcemaps.write()))
      .pipe($.rename({suffix: '.min'}))
      .pipe(gulp.dest(PATHS.dist + '/js'));
  }
}

// Copy images to the "dist" folder
// In production, the images are compressed
function images() {
  return gulp.src('src/img/**/*')
    .pipe($.if(PRODUCTION, $.imagemin({
      progressive: true
    })))
    .pipe(gulp.dest(PATHS.dist + '/img'));
}

// Start a server with BrowserSync to preview the site in
function server(done) {
  browser.init({
    server: PATHS.dist, port: PORT
  });
  done();
}

// Reload the browser with BrowserSync
function reload(done) {
  browser.reload();
  done();
}

// Watch for changes to static assets, pages, Sass, and JavaScript
function watch(jsfile) {
  return function () {
    // gulp.watch(PATHS.assets, copy);
    gulp.watch('src/scss/**/*.scss').on('all', gulp.series(sass, browser.reload));
    gulp.watch('src/js/**/*.js').on('all', gulp.series(javascript(jsfile), browser.reload));
    // gulp.watch('src/img/**/*').on('all', gulp.series(images, browser.reload));
  }
}
