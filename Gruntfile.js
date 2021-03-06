module.exports = function(grunt) {
  // Load grunt tasks automatically
  require('load-grunt-tasks')(grunt);

  var pkg = grunt.file.readJSON('package.json');

  var options = {
    paths: {
      app: 'public',
      assets: 'public/assets',
      dist: 'public_dist', 
      distAssets: 'public_dist/assets',
      html: 'public/html',
      htmlTmp: '.tmp/htmlsnapshot',
      htmlAssets: 'public/html/assets',
      index: 'public_dist/main.php',
      indexDev: 'resources/views/main.php',
      indexTmp: '.tmp/html/main.php'
    },
    pkg: pkg,
	config: {
      src: [
		'grunt_config/*.js*'
	  ]
    }
  };

  // Load grunt configurations automatically
  var configs = require('load-grunt-configs')(grunt, options);
  
  //console.log(configs);

  // Define the configuration for all the tasks
  grunt.initConfig(configs);

  grunt.registerTask('bumper', ['bump-only']);
  grunt.registerTask('css', ['sass']);
  grunt.registerTask('default', ['sass', 'copy:dev', 'watch']);

  grunt.registerTask('shared', [
    'clean:demo',
    'copy:demo',
    'sass',
    'ngconstant',
    'useminPrepare',
    'concat:generated',
    'cssmin:generated',
    'uglify:generated',
    'filerev',
    'usemin',
    'imagemin',
    'usebanner'
  ]);

  grunt.registerTask('demo', [
    'shared',
    'copy:postusemin',
    'grep:demo'
  ]);

  grunt.registerTask('dist', [
    'shared',
    'copy:postusemin',
    'copy:dist',
    'grep:dist',
    //'compress',
    'copy:postusemin',
    'grep:demo',
  ]);
    
  grunt.registerTask('cleanmin', [
    'clean:demo'
  ]);
};
