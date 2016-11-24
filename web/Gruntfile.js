module.exports = function (grunt) {

    grunt.initConfig({
        uglify: {
            options: {
                separator: ';'
            },
            dist: {
                files: {
					'dist/js/main.min.js': 'src/js/main.js',
					'dist/js/home.min.js': 'src/js/home.js',
					'dist/js/utils.min.js': 'src/js/utils.js',
					'dist/js/admin.min.js': 'src/js/admin.js',
					'dist/js/pictogram.edit.min.js': 'src/js/pictogram.edit.js',
					'dist/js/basket.min.js': 'src/js/basket.js',
					'dist/js/orders.min.js': 'src/js/order.js',
					'dist/js/customers.min.js': 'src/js/customers.js',
					'dist/js/order.send.min.js': 'src/js/order.send.js',
					'dist/js/basket.valid.min.js': 'src/js/basket.valid.js',
					'dist/js/customer.min.js': 'src/js/customer.js',
					'dist/js/products.min.js': 'src/js/products.js',
                }
            }
        },
        watch: {
            js: {
                files: [
                    'src/js/*.js',
					'src/css/*.css',
					'src/css/*.scss',
                ],
                tasks: ['uglify', 'sass', 'cssmin'],
                options: {
                }
            }
        },
        sass: {
            options: {
                sourceMap: true
            },
            dist: {
                files: {
                    'dist/css/main.css': 'src/css/main.scss'
                }
            }
        },
		cssmin: {
			options: {
				shorthandCompacting: true,
				roundingPrecision: -1
			},
			target: {
				files: {
					'dist/css/main.min.css': 'dist/css/main.css',
					'dist/css/admin.min.css': 'src/css/admin.css',
					'dist/css/steps.min.css': 'src/css/steps.css',
					'dist/css/login.min.css': 'src/css/login.css',
				}
			}
		}
    });

    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['uglify', 'sass', 'cssmin', 'watch']);
}