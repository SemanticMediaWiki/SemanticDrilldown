<?php

$cfg = require __DIR__ . '/../vendor/mediawiki/mediawiki-phan-config/src/config.php';

$cfg['baseline_path'] = __DIR__ . '/baseline.php';

// Analyse extension source code; vendor + node_modules are excluded by default
$cfg['directory_list'] = array_merge(
	$cfg['directory_list'],
	[
		'includes',
	]
);

$cfg['exclude_analysis_directory_list'] = array_merge(
	$cfg['exclude_analysis_directory_list'],
	[
		'vendor/',
	]
);

// Make dependency extensions visible to Phan's type-checker: those activated
// in the Makefile / ci.yml matrix. mediawiki-phan-config only adds MW core
// and MW vendor to directory_list by default, never extensions/. Without
// this, every call into SemanticMediaWiki's or AdminLinks' classes surfaces
// as PhanUndeclaredClass / PhanUndeclaredClassMethod noise instead of being
// checked against the dependency's actual API.
$IP = getenv( 'MW_INSTALL_PATH' ) !== false
	? str_replace( '\\', '/', getenv( 'MW_INSTALL_PATH' ) )
	: '../..';

$dependencyExtensions = [
	'SemanticMediaWiki',
	'AdminLinks',
];

foreach ( $dependencyExtensions as $ext ) {
	$cfg['directory_list'][] = $IP . '/extensions/' . $ext;
	$cfg['exclude_analysis_directory_list'][] = $IP . '/extensions/' . $ext;
}

// SMW_NS_PROPERTY and its sibling namespace constants are declared at runtime via
// extension.json's "namespaces" block, not via a plain define() in SMW's own PHP source,
// so Phan can't see them from the SemanticMediaWiki directory_list entry above. SMW ships
// a stub with the same define()s it uses for its own Phan run; reuse it here too.
$smwNamespaceStub = $IP . '/extensions/SemanticMediaWiki/.phan/stubs/namespaces.php';
if ( is_file( $smwNamespaceStub ) ) {
	$cfg['file_list'][] = $smwNamespaceStub;
}

return $cfg;
