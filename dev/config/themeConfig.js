'use strict';

module.exports = {
	theme: {
		name: 'skyecruerig',
		author: 'Jason Perrier'
	},
	dev: {
		browserSync: {
			live: true,
			proxyURL: 'skyecruedev.beta',
			bypassPort: '8181'
		},
		browserslist: [ // See https://github.com/browserslist/browserslist
			'> 1%',
			'last 2 versions'
		],
		debug: {
			styles: true, // Render verbose CSS for debugging.
			scripts: false // Render verbose JS for debugging.
		}
	},
	export: {
		compress: false
	}
};
