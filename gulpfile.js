var elixir = require('laravel-elixir');
require('laravel-elixir-css-url-adjuster');
/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix        
    .scripts([
            'bower_components/jquery/dist/jquery.js',
            'bower_components/bootstrap-sass/assets/javascripts/bootstrap.js',
            'bower_components/metisMenu/dist/metisMenu.js',
            'bower_components/jquery-slimscroll/jquery.slimscroll.js',
            'bower_components/sweetalert/dist/sweetalert.min.js',
            'bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
            'bower_components/chosen/chosen.jquery.js',
            "bower_components/jquery-validation/dist/jquery.validate.js",
            "bower_components/validator-js/validator.js",

            "resources/assets/js/custom/custom.js",
            "resources/assets/js/plugins/pace/pace.min.js",
            "resources/assets/js/plugins/dataTables/datatables.min.js",
            "resources/assets/js/plugins/toastr/toastr.min.js",
            "resources/assets/js/plugins/loader/Loading.js",
            "resources/assets/js/plugins/dropzone/dropzone.js",
            "resources/assets/js/custom/image_upload.js",
        ], 'public/js/app.js', './')

        .styles([
            './bower_components/bootstrap/dist/css/bootstrap.css',
            './bower_components/font-awesome/css/font-awesome.css',
            './bower_components/toastr/toastr.css',
            './bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
            './bower_components/chosen/chosen.css',
            './bower_components/animate.css/animate.css',
            './bower_components/sweetalert/dist/sweetalert.css',
            
            "resources/assets/css/plugins/dataTables/datatables.min.css",
            'resources/assets/css/plugins/loader/Loading.css',
            'resources/assets/css/plugins/dropzone/dropzone.css',
            'resources/assets/css/custom/custom.css',
            'resources/assets/css/custom/style.css',
        ], 'public/css/app.css', './')
        .urlAdjuster('public/css/app.css', {}, 'public/css')
        
        .copy([
            'bower_components/bootstrap-sass/assets/fonts/bootstrap'
            ],'public/build/fonts/')
        .copy([
            'bower_components/font-awesome/fonts'
        ], 'public/build/fonts/')
        .copy([
            'bower_components/bootstrap-sass/assets/fonts/bootstrap'
            ],'public/fonts/')
        .copy([
            'bower_components/font-awesome/fonts'
        ], 'public/fonts/')
        .copy('resources/assets/images', 'public/images')
        .copy('resources/assets/images', 'public/build/images')
        .version(['public/css/app.css', 'public/js/app.js', 'public/images']);
});

