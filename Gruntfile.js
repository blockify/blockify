/*!
 * Blockify's Gruntfile (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/blockify/blockify/blob/master/LICENSE)
 */

module.exports = function(grunt) {
  'use strict';

  var path = require('path');
  var source = {
    css: '*/{,**/}*.css',
    less: '*/{,**/}*.less',
    sass: '*/{,**/}*.scss',
    js: '*/{,**/}*.js',
    coffee: '*/{,**/}*.coffee'
  };

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    clean: {
      build: {
        src: 'build/'
      }
    },
    copy: {
      css: {
        expand: true,
        cwd: 'blocks',
        src: source.css,
        dest: 'build/dev'
      },
      js: {
        expand: true,
        cwd: 'blocks',
        src: source.js,
        dest: 'build/dev'
      }
    },
    jshint: {
      options: {
        reporter: require('jshint-stylish'),
      },
      all: 'blocks/{,**/}*.js'
    },
    coffee: {
      development: {
        expand: true,
        cwd: 'blocks',
        src: source.coffee,
        dest: 'build/dev',
        ext: '.coffee.js'
      }
    },
    uglify: {
      production: {
        files: [{
          src: 'build/dev/{,**/}*.js',
          dest: 'build/main.min.js'
        }]
      }
    },
    less: {
      development: {
        options: {
          paths: ["build/dev"]
        },
        files: [{
          expand: true,
          cwd: 'blocks',
          src: source.less,
          dest: 'build/dev',
          ext: '.less.css'
        }]
      }
    },
    sass: {
      development: {
        options: {
          includePaths: [ 'build/dev' ]
        },
        files: [{
          expand: true,
          cwd: 'blocks',
          src: source.sass,
          dest: 'build/dev',
          ext: '.sass.css'
        }]
      }
    },
    cssmin: {
      production: {
        files: [{
          src: 'build/dev/{,**/}*.css',
          dest: 'build/main.min.css'
        }]
      }
    },
    watch: {
      options: {
        livereload: true,
        cwd: 'blocks'
      },
      livereload: {
        files: '*/{,**/}*.{php,json}'
      },
      css: {
        files: source.css,
        tasks: ['build']
      },
      less: {
        files: source.less,
        tasks: ['build']
      },
      sass: {
        files: source.sass,
        tasks: ['build']
      },
      js: {
        files: source.js,
        tasks: ['build']
      },
      coffee: {
        files: source.coffee,
        tasks: ['build']
      }
    },
    filerev: {
      options: {
        encoding: 'utf8',
        algorithm: 'md5',
        length: 8
      },
      build: {
        src: [
          'build/*.min.{js,css}',
          'build/dev/*/{,**/}*.{js,css}'
        ]
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-coffee');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['clean', 'jshint', 'copy', 'less', 'sass', 'cssmin', 'coffee', 'uglify']);
  grunt.registerTask('default', ['build']);
  grunt.registerTask('dev', ['build', 'watch']);

};
