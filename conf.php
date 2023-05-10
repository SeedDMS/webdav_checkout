<?php
$EXT_CONF['webdav_checkout'] = array(
	'title' => 'WebDAV server for checkout directory',
	'description' => "This extension provides access through WebDAV for the user's checkout area",
	'disable' => false,
	'version' => '1.1.2',
	'releasedate' => '2023-05-10',
	'author' => array('name'=>'Uwe Steinmann', 'email'=>'uwe@steinmann.cx', 'company'=>'MMK GmbH'),
	'config' => array(
		'browsermode' => array(
			'title'=>'Allow access with browser',
			'help'=>'Enable this to generate HTML indexes and debug information for your sabre/dav server.',
			'type'=>'checkbox',
		),
		'allow_download' => array(
			'title'=>'Allow download of checked out file',
			'help'=>'Enable this to allow downloading a checked out file from the checkout area.',
			'type'=>'checkbox',
		),
		'allow_download_by_any' => array(
			'title'=>'Allow download by any user',
			'help'=>'Enable this to allow downloading a checked out file by all users with write access on the document.',
			'type'=>'checkbox',
		),
		'allow_upload' => array(
			'title'=>'Allow upload of file',
			'help'=>'Enable this to allow uploading a new file into the checkout area. This overwrites the checked out file in the checkout area.',
			'type'=>'checkbox',
		),
		'allow_upload_by_any' => array(
			'title'=>'Allow upload by any user',
			'help'=>'Enable this to allow uploading a new file by any user with write access on the document. This overwrites the checked out file in the checkout area.',
			'type'=>'checkbox',
		),
		'baseuri' => array(
			'title'=>'Base uri for checkout dir',
			'help'=>"This is the prefix for the path where each checked out document is located. If not set it will default to '/checkout'",
			'type'=>'input',
		),
	),
	'constraints' => array(
		'depends' => array('php' => '7.4.0-', 'seeddms' => array('5.1.23-5.1.99', '6.0.16-6.0.99')),
	),
	'icon' => 'icon.svg',
	'changelog' => 'changelog.md',
	'class' => array(
		'file' => 'class.webdav_checkout.php',
		'name' => 'SeedDMS_ExtWebdavCheckout'
	),
	'language' => array(
		'file' => 'lang.php',
	),
) 
?>
