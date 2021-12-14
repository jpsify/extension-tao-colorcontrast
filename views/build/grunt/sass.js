module.exports = function(grunt) {
    'use strict';

    var root    = grunt.option('root') + '/taoColorContrast/views/';
    var pluginDir = root + 'js/runner/plugins/';

    grunt.config.merge({
        sass : {
            taocolorcontrast: {
                files : [
                    { dest : root + 'css/itemThemeSwitcher.css', src : root + 'scss/itemThemeSwitcher.scss' }
                ]
            },
        },
        watch : {
            taocolorcontrastsass : {
                files : [root + 'scss/**/*.scss', pluginDir + '**/*.scss'],
                tasks : ['sass:taocolorcontrast'],
                options : {
                    debounceDelay : 1000
                }
            }
        },
        notify : {
            taocolorcontrastsass : {
                options: {
                    title: 'Grunt SASS',
                    message: 'SASS files compiled to CSS'
                }
            }
        }
    });

    //register an alias for main build
    grunt.registerTask('taocolorcontrastsass', ['sass:taocolorcontrast']);
};
