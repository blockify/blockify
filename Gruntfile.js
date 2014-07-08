/*!
 * Blockify's Gruntfile (http://blockify.co)
 * Copyright 2014 Blockify
 * Licensed under GNU (https://github.com/blockify/blockify/blob/master/LICENSE)
 */

module.exports = function(grunt) {
  'use strict';

  var BLOCKS_DIR = 'blocks/';

  var blocks = grunt.file.expand({cwd: BLOCKS_DIR}, '*/');

  var config = {
    pkg: grunt.file.readJSON('package.json'),
    clean: {
      build: {
        src: 'build/'
      },
      blocks: {
        expand: true,
        cwd: BLOCKS_DIR,
        src: '*/build/'
      }
    },
    jshint: {
      options: {
        reporter: require('jshint-stylish'),
      },
      all: BLOCKS_DIR + '*/{,**/}*.js'
    },
    less: { },
    sass: { },
    coffee: { },
    cssmin: {
      production: {
        files: [{
          src: BLOCKS_DIR + '*/*.css',
          dest: 'build/main.min.css'
        }]
      }
    },
    uglify: {
      production: {
        files: [{
          src: BLOCKS_DIR + '*/*.js',
          dest: 'build/main.min.js'
        }]
      }
    },
    watch: {
      options: {
        livereload: true,
        cwd: BLOCKS_DIR
      },
      livereload: {
        files: '*/{,**/}*.{php,json}'
      },
      css: {
        files: ['*/{,**/}*.css', '!*/build/*'],
        tasks: ['build']
      },
      less: {
        files: ['*/{,**/}*.less', '!*/build/*'],
        tasks: ['build']
      },
      sass: {
        files: ['*/{,**/}*.scss', '!*/build/*'],
        tasks: ['build']
      },
      js: {
        files: ['*/{,**/}*.js', '!*/build/*'],
        tasks: ['build']
      },
      coffee: {
        files: ['*/{,**/}*.coffee', '!*/build/*'],
        tasks: ['build']
      }
    }
  };

  blocks.forEach(function(blockDir, index) {
    config.less[blockDir] = {
      files: [{
        expand: true,
        flatten: true,
        cwd: BLOCKS_DIR + blockDir,
        src: '{,**/}*.less',
        dest: BLOCKS_DIR + blockDir + 'build/',
        ext: '.less.css'
      }]
    };
    config.sass[blockDir] = {
      files: [{
        expand: true,
        flatten: true,
        cwd: BLOCKS_DIR + blockDir,
        src: '{,**/}*.scss',
        dest: BLOCKS_DIR + blockDir + 'build/',
        ext: '.scss.css'
      }]
    };
    config.coffee[blockDir] = {
      expand: true,
      flatten: true,
      cwd: BLOCKS_DIR + blockDir,
      src: '{,**/}*.coffee',
      dest: BLOCKS_DIR + blockDir + 'build/',
      ext: '.coffee.js'
    };
    //TODO:
    //  Add specific block rebuilding into watch :) - jmswrnr
    //  Allow relative resources in block folders other than js/css
  });

  grunt.initConfig(config);

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-coffee');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('build', ['clean', 'jshint', 'less', 'sass', 'coffee', 'cssmin', 'uglify']);
  grunt.registerTask('default', ['build']);
  grunt.registerTask('dev', ['build', 'watch']);

};
