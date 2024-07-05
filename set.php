<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
include_once '_system.php';
include_once 'functions.php';
if(isset($_GET['logout'])) {
	_query("UPDATE login SET _status='logout' WHERE _token='".$_COOKIE['_token']."'");
	setcookie("_device",null,time()-1,"/");
	setcookie("_token",null,time()-1,"/");
	setcookie("_uid",null,time()-1,"/");
	exit("UPDATE login SET _status='logout' WHERE _token='".$_COOKIE['_token']."'");
}
?>