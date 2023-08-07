<?php
if(!empty($_SERVER['SEEDDMS_HOME']))
	require_once($_SERVER['SEEDDMS_HOME'].'/inc/inc.Settings.php');
else
	require_once("../../../inc/inc.Settings.php");

require_once("inc/inc.LogInit.php");
require_once("inc/inc.Init.php");
require_once("inc/inc.Extension.php");
require_once("inc/inc.DBInit.php");

// Include SabreDAV
require_once 'vendor/autoload.php';

// settings
date_default_timezone_set('Europe/Berlin');

/* The baseUri is what will be entered in the dav client. This is either the
 * path of this file or an alias set up in the apache config. The prefered
 * method is to set up an alias
 * Alias /checkout /home/www-data/seeddms/www/ext/webdav_checkout/op/remote.php
 * The dav server treats everything after the base uri as the
 * name of the requested file
 */
if(empty($settings->_extensions['webdav_checkout']['baseuri']))
	$baseUri = '/checkout';
else
	$baseUri = ($settings->_extensions['webdav_checkout']['baseuri'][0] != '/' ? '/' : '').$settings->_extensions['webdav_checkout']['baseuri'];

/* Set temp dir for locking */
$publicDir = '/tmp';
$tmpDir = $publicDir.'/.tmpdata';

$user = null;

function checkuser($username, $pass) { /* {{{ */
	global $dms, $settings, $user, $server, $baseUri, $logger, $authenticator;
	$logger->log('\'webdav_checkout\': login in as user \''.$username.'\'', PEAR_LOG_INFO);
	$userobj = $dms->getUserByLogin($username);
	if(!$userobj) {
		$logger->log('\'webdav_checkout\': no such user', PEAR_LOG_ERR);
		return false;
	}
	$userobj = $authenticator->authenticate($username, $pass);
	if(!is_object($userobj)) {
		$logger->log('\'webdav_checkout\': wrong password', PEAR_LOG_ERR);
		return false;
	}

	$user = $userobj;
	$rootdir = sprintf($settings->_checkOutDir.'/', preg_replace('/[^A-Za-z0-9_-]/', '', $user->getLogin()));
	if(!file_exists($rootdir))
		mkdir($rootdir);
	$root =  new \Sabre\DAV\FS\Directory($rootdir);
	$server->tree = new \Sabre\DAV\Tree($root);
//	$baseUri = '/checkout/'.preg_replace('/[^A-Za-z0-9_-]/', '', $user->getLogin());
  $server->setBaseUri($baseUri);

	return true;
} /* }}} */

// Create the root node
// The initial path is set to the temp directory
// During authentication it will be set to the users checkout dir
$root = new \Sabre\DAV\FS\Directory($publicDir);

// The rootnode needs in turn to be passed to the server class
$server = new \Sabre\DAV\Server($root);

if (isset($baseUri))
  $server->setBaseUri($baseUri);

// Support for LOCK and UNLOCK
$lockBackend = new \Sabre\DAV\Locks\Backend\File($tmpDir . '/locksdb');
$lockPlugin = new \Sabre\DAV\Locks\Plugin($lockBackend);
$server->addPlugin($lockPlugin);

// Support for html frontend
if(!empty($settings->_extensions['webdav_checkout']['browsermode'])) {
	$browser = new \Sabre\DAV\Browser\Plugin();
	$server->addPlugin($browser);
}

// Automatically guess (some) contenttypes, based on extension
$server->addPlugin(new \Sabre\DAV\Browser\GuessContentType());

// Authentication backend
//$authBackend = new \Sabre\DAV\Auth\Backend\File('.htdigest');
//$authBackend->setRealm('seeddms');
$authBackend = new \Sabre\DAV\Auth\Backend\BasicCallBack('checkuser');
$auth = new \Sabre\DAV\Auth\Plugin($authBackend);
$server->addPlugin($auth);

// Temporary file filter
$tempFF = new \Sabre\DAV\TemporaryFileFilterPlugin($tmpDir);
$server->addPlugin($tempFF);

// And off we go!
$server->exec();
