var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/resources/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/resources')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('lib/semantic/js/semantic', './assets/lib/semantic/dist/semantic.js')
    .addStyleEntry('lib/semantic/css/semantic', './assets/lib/semantic/dist/semantic.css')
    .addStyleEntry('css/layout', './assets/sass/layout.sass')
    // .addStyleEntry('css/app', './assets/css/app.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
