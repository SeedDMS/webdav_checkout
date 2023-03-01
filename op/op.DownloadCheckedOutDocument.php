<?php
//    MyDMS. Document Management System
//    Copyright (C) 2010-2023 Uwe Steinmann
//
//    This program is free software; you can redistribute it and/or modify
//    it under the terms of the GNU General Public License as published by
//    the Free Software Foundation; either version 2 of the License, or
//    (at your option) any later version.
//
//    This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//    You should have received a copy of the GNU General Public License
//    along with this program; if not, write to the Free Software
//    Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

if(!empty($_SERVER['SEEDDMS_HOME']))
	require_once($_SERVER['SEEDDMS_HOME'].'/inc/inc.Settings.php');
else
	require_once("../../../inc/inc.Settings.php");
require_once("inc/inc.Utils.php");
require_once("inc/inc.LogInit.php");
require_once("inc/inc.Language.php");
require_once("inc/inc.Init.php");
require_once("inc/inc.Extension.php");
require_once("inc/inc.DBInit.php");
require_once("inc/inc.ClassUI.php");
require_once("inc/inc.Authentication.php");

// document download
if (!isset($_GET["documentid"]) || !is_numeric($_GET["documentid"]) || intval($_GET["documentid"])<1) {
	UI::exitError(getMLText("document_title", array("documentname" => getMLText("invalid_doc_id"))),getMLText("invalid_doc_id"));
}

$documentid = $_GET["documentid"];
$document = $dms->getDocument($documentid);

/* Do not show checkout info to user without write access */
if ($document->getAccessMode($user) <= M_READ) {
UI::exitError(getMLText("document_title", array("documentname" => $document->getName())),getMLText("access_denied"));
}

$infos = $document->getCheckOutInfo();
if($infos) {
	$checkoutstatus = $document->checkOutStatus();
	$info = $infos[0];
	if(file_exists($info['filename'])) {
		$content = $document->getContentByVersion($info['version']);
		header("Content-Transfer-Encoding: binary");
		$efilename = rawurlencode($content->getOriginalFileName());
		header("Content-Disposition: attachment; filename=\"" . $efilename . "\"; filename*=UTF-8''".$efilename);
		header("Content-Type: " . $content->getMimeType());
		header("Cache-Control: must-revalidate");

		sendFile($info['filename']);
	}
}
UI::exitError(getMLText("document_title", array("documentname" => $document->getName())),getMLText("access_denied"));
