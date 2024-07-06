<?php
header('Content-Type: text/javascript');
$DOCUMENT_ROOT = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : "C:/xampp/htdoc";
$root = $DOCUMENT_ROOT.'/';
include_once $root.'/set.php';
if(_login()) {
	include_once $root.'/jsFolders/login.js';
}
else {
	include_once $root.'/jsFolders/nologin.js';
}

include_once $root.'/jsFolders/admin.js';
include_once $root.'/jsFolders/main.js';
