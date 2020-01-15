import gulp from "gulp";
import browserify from "browserify";
import uglify from "gulp-uglify";
import babelify from "babelify";
import source from "vinyl-source-stream";
import buffer from "vinyl-buffer";
import rename from "gulp-rename";
import cleanCSS from "gulp-clean-css";
import sass from "gulp-sass";
import del from "del";
import imagemin from "gulp-imagemin";
import browserSyncPackage from "browser-sync";

const browserSync = browserSyncPackage.create();

const { reload } = browserSync;

const paths = {
  styles: {
    src: "src/css/sass/style.scss",
    dest: "dist/"
  },
  scripts: {
    dev: "src/js/**/*.js",
    src: "src/js/index.js",
    dest: "dist/scripts/"
  },
  root: {
    src: [
      "src/*.{php,png,css}",
      "src/modules/*.php",
      "src/img/**/*.{jpg,png,svg,gif,webp,ico}",
      "src/fonts/*.{woff,woff2,ttf,otf,eot,svg}",
      "src/languages/*.{po,mo,pot}"
    ],
    files: "dist/**/*.*",
    dest: "dist/"
  },
  images: {
    src: "src/img/*",
    dest: "dist/img"
  },
  favicon: {
    src: "src/favicon/favicon.png",
    dest: "dist/"
  }
};

export const clean = () => del(["dist"]);

export function styles() {
  return gulp
    .src(paths.styles.src)
    .pipe(sass().on("error", sass.logError))
    .pipe(cleanCSS())
    .pipe(
      rename({
        basename: "style"
      })
    )
    .pipe(gulp.dest(paths.styles.dest));
}

export function stylesDev() {
  return gulp
    .src(paths.styles.src)
    .pipe(sass().on("error", sass.logError))
    .pipe(cleanCSS())
    .pipe(
      rename({
        basename: "style"
      })
    )
    .pipe(gulp.dest(paths.styles.dest))
    .pipe(browserSync.stream());
}

export function scripts() {
  return browserify(paths.scripts.src)
    .transform("babelify", { presets: ["@babel/preset-env"] })
    .bundle()
    .pipe(source("main.min.js"))
    .pipe(buffer())
    .pipe(gulp.dest(paths.scripts.dest));
}

export function minifyScripts() {
  return browserify(paths.scripts.src)
    .transform("babelify", { presets: ["@babel/preset-env"] })
    .bundle()
    .pipe(source("main.min.js"))
    .pipe(buffer())
    .pipe(uglify())
    .pipe(gulp.dest(paths.scripts.dest));
}

export function copy() {
  return gulp
    .src(paths.root.src, {
      base: "src"
    })
    .pipe(gulp.dest(paths.root.dest));
}

export function copyDev(file) {
  return gulp.src(file, { base: "./src/" }).pipe(gulp.dest(paths.root.dest));
}

export function images() {
  return gulp
    .src(paths.images.src)
    .pipe(
      imagemin([
        imagemin.gifsicle({ interlaced: true }),
        imagemin.jpegtran({ progressive: true }),
        imagemin.optipng({ optimizationLevel: 5 }),
        imagemin.svgo({
          plugins: [{ removeViewBox: true }, { cleanupIDs: false }]
        })
      ])
    )
    .pipe(gulp.dest(paths.images.dest));
}

export function imagesDev() {
  return gulp.src(paths.images.src).pipe(gulp.dest(paths.images.dest));
}

export function gulplisten() {
  gulp.watch(paths.styles.src, stylesDev);
  gulp.watch([paths.scripts.dev], scripts);
  gulp.watch(paths.root.src).on("change", copyDev);
  gulp.watch([paths.images.src], imagesDev);
}

export function server() {
  browserSync.init(paths.root.files, {
    proxy: "localhost:8800",
    ghostMode: false
  });

  gulp.watch([paths.root.files]).on("change", reload);
}

const dev = gulp.series(
  copy,
  styles,
  scripts,
  imagesDev,
  gulp.parallel([server, gulplisten])
);

gulp.task("dev", dev);

const build = gulp.series(copy, gulp.parallel(styles, minifyScripts, images));

gulp.task("build", build);

export default build;
