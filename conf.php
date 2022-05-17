<?php
$EXT_CONF['webdav_checkout'] = array(
	'title' => 'WebDAV server for checkout directory',
	'description' => "This extension provides access through WebDAV for the user's checkout area",
	'disable' => false,
	'version' => '1.0.0',
	'releasedate' => '2022-05-17',
	'author' => array('name'=>'Uwe Steinmann', 'email'=>'uwe@steinmann.cx', 'company'=>'MMK GmbH'),
	'config' => array(
		'browsermode' => array(
			'title'=>'Allow access with browser',
			'help'=>'Enable this to generate HTML indexes and debug information for your sabre/dav server.',
			'type'=>'checkbox',
		),
		'baseuri' => array(
			'title'=>'Base uri for checkout dir',
			'help'=>"This is the prefix for the path where each checked out document is located. If not set it will default to '/checkout'",
			'type'=>'input',
		),
	),
	'constraints' => array(
		'depends' => array('php' => '5.6.40-', 'seeddms' => array('5.1.23-5.1.99', '6.0.16-6.0.99')),
	),
	'icon' => 'icon.svg',
	'changelog' => 'changelog.md',
//	'class' => array(
//		'file' => 'class.calcarddav_server.php',
//		'name' => 'SeedDMS_ExtCalCardDavServer'
//	),
	'language' => array(
		'file' => 'lang.php',
	),
) 
?>
