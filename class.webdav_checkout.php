<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2023 Uwe Steinmann <uwe@steinmann.cx>
*  All rights reserved
*
*  This script is part of the SeedDMS project. The SeedDMS project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * webdav_checkout extension
 *
 * @author  Uwe Steinmann <uwe@steinmann.cx>
 * @package SeedDMS
 * @subpackage  webdav_checkout
 */
class SeedDMS_ExtWebdavCheckout extends SeedDMS_ExtBase { /* {{{ */

	/**
	 * Initialization
	 *
	 * Use this method to do some initialization like setting up the hooks
	 * You have access to the following global variables:
	 * $GLOBALS['settings'] : current global configuration
	 * $GLOBALS['settings']->_extensions['example'] : configuration of this extension
	 * $GLOBALS['LANG'] : the language array with translations for all languages
	 * $GLOBALS['SEEDDMS_HOOKS'] : all hooks added so far
	 */
	function init() { /* {{{ */
		$GLOBALS['SEEDDMS_HOOKS']['view']['viewDocument'][] = new SeedDMS_ExtWebdavCheckout_ViewDocument;
		$GLOBALS['SEEDDMS_HOOKS']['view']['style'][] = new SeedDMS_ExtWebdavCheckout_Main;
	} /* }}} */

	function main() { /* {{{ */
	} /* }}} */
} /* }}} */

/**
 * Class containing methods for hooks when a html page is started
 *
 * @author  Uwe Steinmann <uwe@steinmann.cx>
 * @package SeedDMS
 * @subpackage webdav_checkout
 */
class SeedDMS_ExtWebdavCheckout_Main { /* {{{ */
	/**
	 * Hook called at start of page
	 */
	function startPage($view) { /* {{{ */
		$settings = $view->getParam('settings');
		ob_start();
		$view->printFileChooserJs();
		$jscode = ob_get_contents();
	 	ob_end_clean();
		$view->addFooterJS($jscode);
	} /* }}} */

} /* }}} */

/**
 * Class containing methods for hooks when a document is added
 *
 * @author  Uwe Steinmann <uwe@steinmann.cx>
 * @package SeedDMS
 * @subpackage  webdav_checkout
 */
class SeedDMS_ExtWebdavCheckout_ViewDocument { /* {{{ */

	/**
	 * Hook for returning checkout info
	 */
	function checkOutInfo($view, $document) { /* {{{ */
		$dms = $view->getParam('dms');
		$user = $view->getParam('user');
		$settings = $view->getParam('settings');
		$config = $settings->_extensions['webdav_checkout'];

		/* Do not show download option to users if turned off in configuration */
		if(empty($config['allow_download']) && empty($config['allow_upload']))
			return null;

		/* Do not show download option to users without write access */
		if($document->getAccessMode($user) <= M_READ)
			return null;

		$infos = $document->getCheckOutInfo();
		$html = '';
		if($infos) {
			$checkoutstatus = $document->checkOutStatus();
			$info = $infos[0];
			$checkoutuser = $dms->getUser($info['userID']);
			$allowdownload = !empty($config['allow_download']) && (!empty($config['allow_download_by_any']) || ($checkoutuser->getId() == $user->getId()));
			$allowupload = !empty($config['allow_upload']) && (!empty($config['allow_upload_by_any']) || ($checkoutuser->getId() == $user->getId()));
			if($allowdownload || $allowupload) {
				$html .= "<div class=\"alert alert-info\">";
				if($checkoutstatus != 1) {
					if($allowdownload) {
						$html .= '<p><a href="'.$settings->_httpRoot.'ext/webdav_checkout/op/op.DownloadCheckedOutDocument.php?documentid='.$document->getID().'&version='.$info['version'].'" target="webdav"><i class="fa fa-download"></i> '.getMLText("webdav_checkout_download").'</a></p>';
					}
					if($allowupload) {
						$html .= '<p><form action="'.$settings->_httpRoot.'ext/webdav_checkout/op/op.UploadCheckedOutDocument.php" enctype="multipart/form-data" method="post"><input type="hidden" name="documentid" value="'.$document->getID().'"><input type="hidden" name="version" value="'.$info['version'].'">'.$view->getFileChooserHtml('checkoutfile', false).'<br><button class="btn btn-primary" type="submit">'.getMLText('webdav_checkout_upload').'</button></form></p>';
					}
				}
				$html .= "</div>";
			}
		}
		return $html;
	} /* }}} */

} /* }}} */


