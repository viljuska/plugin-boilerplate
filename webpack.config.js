const path                   = require( 'path' ),
      glob                   = require( 'glob' ),
      MiniCssExtractPlugin   = require( 'mini-css-extract-plugin' ),
      { CleanWebpackPlugin } = require( 'clean-webpack-plugin' ),
      webpack                = require( 'webpack' ),
      rootPath               = process.cwd(),
      paths                  = {
	      root  : `/wp-content/plugins/${ path.basename( path.resolve( './' ) ) }`,
	      assets: path.resolve( rootPath, 'assets' ),
	      dist  : path.resolve( rootPath, 'dist' ),
      },
      // Register all assets for building (scss, js), but exclude all files that starts with underscore (_) as they represent modules and not full files
      globPatterns           = {
	      include: `${ paths.assets }/{scripts/*.js*,styles/*.{sc,sa,c}ss}`,
	      exclude: [ `${ paths.assets }/{scripts/_*.js*,styles/_*.scss}` ],
      },
      entry                  = paths => {
	      const entries = {};

	      for ( const filePath of paths ) {
		      const file_name = path.basename( filePath, path.extname( filePath ) );

		      // check if there is already array with same file name
		      if ( entries[ file_name ] ) {
			      entries[ file_name ].push( filePath );
			      continue;
		      }

		      // Add file path in array because we can have multiple assets with same file name e.g. main.scss and main.js
		      entries[ file_name ] = [ filePath ];
	      }

	      return entries;
      };

module.exports = ( env, argv ) => {
	// Get correct pattern based on if user build admin or front assets
	const files        = glob.sync( globPatterns.include, {
		      ignore: globPatterns.exclude,
	      } ),
	      isProduction = argv.mode === 'production';

	return {
		mode: isProduction ? 'production' : 'development',

		optimization: {
			chunkIds            : 'named',
			mergeDuplicateChunks: true,
		},

		stats: {
			hash        : false,
			version     : false,
			timings     : true,
			children    : true,
			errors      : true,
			errorDetails: true,
			errorStack  : false,
			warnings    : false,
			chunks      : false,
			modules     : false,
			reasons     : false,
			source      : false,
			publicPath  : false,
			assets      : true,
			assetsSort  : '!size',
			logging     : 'none', // false | error | warn | info | verbose | true
			loggingTrace: true,
		},

		resolve: {
			symlinks: false,
			modules : [ 'node_modules' ],
			alias   : {
				'@modules': path.resolve( __dirname, 'assets/scripts/modules/' ),
			},
		},

		entry: entry( files ),

		context: paths.assets,

		output: {
			filename           : 'scripts/[name].js',
			path               : paths.dist,
			publicPath         : `${ paths.root }/${ path.basename( paths.dist ) }/`,
			assetModuleFilename: '[path][name][ext]',
			clean              : true,
			pathinfo           : false,
		},

		module: {
			rules: [
				{
					test   : /\.js$/i,
					exclude: /node_modules/,
					use    : [
						{
							loader : 'babel-loader',
							options: {
								presets: [ '@babel/preset-env' ],
							},
						},
					],
				},
				{
					test   : /\.(s[ac]|c)ss$/i,
					exclude: /node_modules/,
					use    : [
						MiniCssExtractPlugin.loader,
						'css-loader',
						'postcss-loader',
						'sass-loader',
					],
				},
				{
					test  : /\.(png|svg|jpe?g|gif|webp)$/i,
					type  : 'asset',
					parser: {
						dataUrlCondition: {
							maxSize: 10 * 1024, // Default 8kb, set to 10kb
						},
					},
				},
				{
					test: /\.(woff2?|eot|ttf|otf)$/i,
					type: 'asset',
				},
			],
		},

		target: 'browserslist',

		plugins: [
			new webpack.ProgressPlugin( {
				handler          : ( percentage, message, ...args ) => {
					console.info( `\u001b[A\u001b[K\u001b[33m${ ( percentage * 100 ).toFixed( 2 ) }%` +
					              `\t\u001b[0m\u001b[1m${ message }\t` +
					              `\u001b[0m\u001b[90m${ args && args.length > 0 ? args[ 0 ] : '' }\u001b[0m` );
				},
				activeModules    : false,
				entries          : false,
				modules          : true,
				modulesCount     : 5000,
				profile          : true,
				dependencies     : true,
				dependenciesCount: 10000,
				percentBy        : 'entries',
			} ),
			new CleanWebpackPlugin(),
			new MiniCssExtractPlugin( {
				filename: 'styles/[name].css',
			} ),
			// Autoload required files
			new webpack.ProvidePlugin( {
				regeneratorRuntime: 'regenerator-runtime', // Necessary for using dynamic imports with Babel
			} ),
			new webpack.AutomaticPrefetchPlugin(),
			// new CopyWebpackPlugin( {
			// 	                       patterns: [
			// 		                       {
			// 			                       from:             './images/**/*',
			// 			                       to:               '[path][name][ext]',
			// 			                       noErrorOnMissing: true
			// 		                       }
			// 	                       ]
			//                        } )
		],

		devtool: !isProduction ? 'source-map' : false,
	};
};
