const path = require( 'path' );

module.exports = {
	'bumpFiles': [
		{
			'filename': 'composer.json',
			'type'    : 'json',
		},
		{
			'filename': 'composer.lock',
			'type'    : 'json',
		},
		{
			'filename': 'package.json',
			'type'    : 'json',
		},
		{
			'filename': 'package-lock.json',
			'type'    : 'json',
		},
		{
			'filename': path.basename( __dirname ) + '.php',
			'updater' : 'plain-text-version-updater',
		},
	],
};
