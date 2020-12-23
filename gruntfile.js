module.exports = function(grunt) {

    // 1. All configuration goes here
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        concat: {
            basic_and_extras: {
                files: {
                    'js/build/production.js': ['js/fitvids.js', 'js/placeholders.js', 'js/functions.js'],
                    'js/build/customizer.js': ['js/customizer.js', 'js/multiple-select.js']
                }
            }
        },
        uglify: {
            dist: {
                files: {
                    'js/build/production.min.js': 'js/build/production.js',
                    'js/build/customizer.min.js': 'js/build/customizer.js',
                    'js/build/postMessage.min.js': 'js/postMessage.js',
                    'js/build/profile-uploader.min.js': 'js/profile-uploader.js'
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
                files: {
                    'style.css': 'sass/style.scss',
                    'styles/customizer-style.css': 'sass/customizer.scss',
                    'styles/admin-style.css': 'sass/admin.scss',
                    'styles/editor-style.css': 'sass/editor_style.scss'
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
                    type: 'wp-theme',
                    processPot: function( pot ) {
                        var translation,
                            excluded_meta = [
                                'Theme Name of the plugin/theme',
                                'Theme URI of the plugin/theme',
                                'Author of the plugin/theme',
                                'Author URI of the plugin/theme'
                            ];

                        for ( translation in pot.translations[''] ) {
                            if ( 'undefined' !== typeof pot.translations[''][ translation ].comments.extracted ) {
                                if ( excluded_meta.indexOf( pot.translations[''][ translation ].comments.extracted ) >= 0 ) {
                                    console.log( 'Excluded meta: ' + pot.translations[''][ translation ].comments.extracted );
                                    delete pot.translations[''][ translation ];
                                }
                            }
                        }

                        return pot;
                    }
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
        },
        excludeFiles: '--exclude "*.gitignore" --exclude ".sass-cache/" --exclude "*.DS_Store" --exclude ".git/" --exclude ".idea/" --exclude "gruntfile.js" --exclude "node_modules/" --exclude "package.json" --exclude "sass/" --exclude "style.css.map" --exclude "styles/admin-style.css.map" --exclude "styles/customizer-style.css.map" --exclude "styles/editor-style.css.map"',
        shell: {
            zip: {
                command: [
                    // delete existing copies (if they exist)
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/ignite" || true',
                    'rm -R "/Users/bensibley/Dropbox/Compete Themes/Distribution/ignite.zip" || true',
                    // copy folder without any project/meta files
                    'rsync -r "/Users/bensibley/Sites/ignite/wp-content/themes/ignite" "/Users/bensibley/Dropbox/Compete Themes/Distribution/" <%= excludeFiles %>',
                    // open dist folder
                    'cd "/Users/bensibley/Dropbox/Compete Themes/Distribution/"',
                    // zip the ignite folder
                    'zip -r ignite.zip ignite'
                ].join('&&')
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
    grunt.loadNpmTasks('grunt-shell');

    // 4. Where we tell Grunt what to do when we type "grunt" into the terminal.
    grunt.registerTask('default', ['concat', 'uglify', 'watch', 'sass', 'autoprefixer', 'cssmin', 'compress', 'makepot', 'phpcs', 'phpunit', 'shell']);

};