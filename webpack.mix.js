const mix = require('laravel-mix')
require('vuetifyjs-mix-extension')
const { VuetifyPlugin } = require('webpack-plugin-vuetify')
const path = require('path')
const Dotenv = require('dotenv-webpack')

mix.setPublicPath('public/dist')
	.setResourceRoot('/dist/')
	.js('resources/js/index.js', 'index.js')
	.vuetify()
	.vue({ version: 3, extractStyles: 'styles.css' })
	.options({
		processCssUrls: false,
	})
	.webpackConfig({
		plugins: [new Dotenv(), new VuetifyPlugin()],
		resolve: {
			alias: {
				'@': path.resolve(__dirname, 'resources/js'),
			},
		},
	})
