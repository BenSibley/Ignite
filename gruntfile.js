module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            basic_and_extras: {
                files: {
                    'js/build/production.js': ['js/fitvids.js', 'js/placeholders.js', 'js/functions.js']
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'js/build/production.min.js': 'js/build/production.js',
                    'js/build/multiple-select.min.js': 'js/multiple-select.js',
                    'js/build/customizer.min.js': 'js/customizer.js',
                    'js/build/postMessage.min.js': 'js/postMessage.js'
                }
            }
        },
        watch: {
            scripts: {
                files: ['js/*.js'],
                tasks: ['concat', 'uglify'],
                options: {
                    spawn: false
                }
            },
            css: {
                files: ['sass/*.scss'],
                tasks: ['sass', 'autoprefixer', 'cssmin'],
                options: {
                    livereload: true,
                    spawn: false
                }
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'style.css': 'sass/style.scss',
                    'styles/customizer-style.css': 'sass/customizer.scss',
                    'styles/admin-style.css': 'sass/admin.scss'
                }
            }
        },
        autoprefixer: {
            dist: {
                options: {
                    browsers: ['last 1 version', '> 1%', 'ie 8']
                },
                files: {
                    'style.css': 'style.css',
                    'styles/customizer-style.css': 'styles/customizer-style.css',
                    'styles/admin-style.css': 'styles/admin-style.css'
                }
            }
        },
        cssmin: {
            combine: {
                files: {
                    'style.min.css': ['style.css'],
                    'styles/customizer-style.min.css': ['styles/customizer-style.css'],
                    'styles/admin-style.min.css': ['styles/admin-style.css']
                }
            }
        },
        compress: {
            main: {
                options: {
                    mode: 'zip',
                    archive: '/Users/bensibley/Desktop/ignite.zip'
                },
                files: [
                    {
                        expand: true,
                        src: ['**', '!node_modules/**','!sass/**', '!gruntfile.js', '!package.json', '!style-prefixed.css','!/.git/','!/.idea/','!/.sass-cache/','!**.DS_Store'],
                        filter: 'isFile'
                    }
                ]
            }
        },
        makepot: {
            target: {
                options: {
                    domainPath: '/languages',
                    exclude: ['library/.*/.*'],
                    potFilename: 'ignite.pot',
                    type: 'wp-theme'
                }
            }
        },
        phpcs: {
            application: {
                dir: ['*.php']
            },
            options: {
                tabWidth: 4
            }
        },
        phpunit: {
            classes: {
                dir: 'tests/php/'
            },
            options: {
                bin: 'vendor/bin/phpunit',
                bootstrap: 'tests/php/phpunit.php',
                colors: true
            }
        }
    });

    // 3. Where we tell Grunt we plan to use this plug-in.
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-compress');
    grunt.loadNpmTasks('grunt-wp-i18n');
    grunt.loadNpmTasks('grunt-phpcs');
    grunt.loadNpmTasks('grunt-phpunit');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'watch', 'sass', 'autoprefixer', 'cssmin', 'compress', 'makepot', 'phpcs', 'phpunit']);

};