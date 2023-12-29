function extractVersion( string ) {
	const pattern = /Version:\s+(\d+(\.\d+)+)/;
	const match   = string.match( pattern );
	if ( match ) {
		return match[ 1 ];
	} else {
		return null;
	}
}

function replaceVersion( string, newVersion ) {
	const pattern     = /(Version:\s+)\d+(\.\d+)+/;
	const replacement = '$1' + newVersion;
	return string.replace( pattern, replacement );
}

module.exports.readVersion = function ( contents ) {
	return extractVersion( contents );
};

module.exports.writeVersion = function ( contents, version ) {
	return replaceVersion( contents, version );
};
