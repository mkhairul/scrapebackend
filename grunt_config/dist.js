module.exports.tasks = {
  copy: {
    dist: {
      files: [
        { src: 'Gruntfile.js', dest: '.tmp/Gruntfile.js' },
        { cwd: '<%= paths.distAssets %>/css', 
          expand: true,
          src: '*', 
          dest: 'public/assets/css',
		  rename: function(dest, src){
			return dest + '/' + src.split('.')[0] + '.min.css';
		  }
        },
        { cwd: '<%= paths.distAssets %>/js', 
          expand: true,
          src: '*', 
          dest: 'public/assets/js',
		  rename: function(dest, src){
			return dest + '/' + src.split('.')[0] + '.min.js';
		  }
        }
      ]
    }
  },

  // lightweight env replacements for development
  grep: {
    dist: {
      files: {
        '<%= paths.index %>': ['<%= paths.index %>'],
        '.tmp/Gruntfile.js': ['.tmp/Gruntfile.js']
      },
      options: {
        pattern: 'dist',
        fileOverride: true
      }
    }
  },

  // make a distribution of our application / theme
  compress: {
    main: {
      options: {
        archive: 'releases/<%= pkg.name %>.<%= pkg.version %>.zip'
      },
      files: [
        {
          expand: true, src: [
            '<%= paths.assets %>/**',
            'config/**',
            '<%= paths.dist %>/**',
            '<%= paths.html %>/**',
            '<%= paths.app %>/pages/**',
            '<%= paths.app %>/psd/**',
            '<%= paths.app %>/index.dev.html',
            '.bowerrc', '.gitignore', 'bower.json', 'CHANGELOG.md', 'package.json', 'README.md',
            '!<%= paths.html %>/build/**',
            '!config/html.js',
            '!releases/**',
            '!app/landing/**',
            '!<%= paths.assets %>/js/app.min.js',
            '!<%= paths.assets %>/js/vendor.min.js',
          ],
          dest: '.'
        },
        { expand: true, cwd: '.tmp/', src: ['Gruntfile.js'], dest: '.' }
      ]
    }
  },

};
