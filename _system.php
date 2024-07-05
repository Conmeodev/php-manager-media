<?php
session_start();
include_once 'functions.php';
include_once '_config.php';

$ketnoi = mysqli_connect($db_host,$db_user,$db_pass,$db_name);
if($ketnoi) {
	mysqli_set_charset($ketnoi,'utf8mb4');
} else {
	header("location:/install");
}
$_http = 'http://';
$_domain = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '127.0.0.1';
define("_domain_", $_http.$_domain);

$cookie_thietbi = isset($_COOKIE['_device']) ? $_COOKIE['_device'] : null;
$cookie_token = isset($_COOKIE['_token']) ? $_COOKIE['_token'] : null;
$cookie_uid = isset($_COOKIE['_uid']) ? $_COOKIE['_uid'] : null;
$uid = 0;

if(isset($cookie_uid)) {
	$login_check = _fetch("SELECT * FROM login WHERE _token='$cookie_token'");
	if(isset($login_check['id'])){
		if(_m5($login_check['uid'], $cookie_uid) && _m5($login_check['_device'] , $cookie_thietbi) && _m5($login_check['_status'] , "login")) {
			$zuser = info_user('id',$cookie_uid);
			if(isset($zuser['id'])) {
				$uid = $zuser['id'];
				
				if(_m5($zuser['_username'],null)) {
					$_name = htmlspecialchars($zuser['_user']);
				} else {
					$_name = htmlspecialchars($zuser['_username']);
				}
			}
		}
	}
}

function _query($txt){
	GLOBAL $ketnoi;
	return mysqli_query($ketnoi,$txt);
}
function _fetch($txt) {
	return mysqli_fetch_array(_query($txt));
}
function w_fetch($txt) {
	return mysqli_fetch_array($txt);
}
function _sql($txt){
	GLOBAL $ketnoi;
	return mysqli_real_escape_string($ketnoi,$txt);
}



function _login(){
	GLOBAL $uid;
	if($uid <= 0) {
		return false;
	} else {
		return true;
	}
}
function info_user($col,$str) {
	$user = _fetch("SELECT * FROM user WHERE $col='$str'");
	if(isset($user['id'])) {
		return $user;
	} else {
		return false;
	}
}
function info_file($col,$str) {
	$file = _fetch("SELECT * FROM file WHERE $col='$str' and _status='active'");
	if(isset($file['id'])) {
		return $file;
	} else {
		return false;
	}
}
function _user($id){
	return _fetch("SELECT * FROM user WHERE id='$id'");
}
function _file($id){
	$file = _fetch("SELECT * FROM file WHERE id='$id' and _status='active'");
	if(isset($file['id'])) {
	    return $file;
	} else {
	    return false;
	}
}

function _folders($id){
	$file = _fetch("SELECT * FROM folders WHERE id='$id' and _status='active'");
	if(isset($file['id'])) {
	    return $file;
	} else {
	    return false;
	}
}
function info_folders($col,$str) {
	$user = _fetch("SELECT * FROM folders WHERE $col='$str' and _status='active'");
	if(isset($user['id'])) {
		return $user;
	} else {
		return false;
	}
}
function _template($id,$type) {
    if(_m5($type,'folders')) {
        return '<div class="box" _idFolders="'.$id.'" id="folders'.$id.'" onclick="_openFolders(\''.$id.'\')">
        <div class="thumb" style="background-image:url(/images/folders.png)"></div>
        <div class="info">
        <span class="name">'.htmlspecialchars(_folders($id)['_name']).'</span>
        </div>
        </div>';
    }
    if(_m5($type,'file')) {
        return '<div id="file'.$id.'" class="box box_files" _thumb="'._file($id)["_thumb"].'" _type="'._file($id)["_type"].'" _dir="/uploads/'._file($id)["_dir"].'" _size="'._file($id)["_size"].'" _time="'._file($id)["_time"].'" onclick="_open_file(this)" _idFiles="'.$id.'" >
        <div class="thumb"></div>
        <div class="info">
        <span class="name">'._file($id)["_name"].'</span>
        </div>
        </div>';
    }
}


function auto_login ($uid, $stime = 999999999999) {
	$user = info_user("id",$uid);
	$_txt = $user['_user']."|--|"._device("name")."|--|"._device("ip")."|--|".date("H:i:s | d-m-Y");
	$_token = md5($_txt);
	$_device = md5($_token._device("name").time());

	$insert = _query("INSERT INTO login(uid,_time,_token,_status,_content,_device) VALUES('$uid','".time()."','$_token','login','$_txt','$_device')");

	if($insert) {
		setcookie("_device",$_device,time()*$stime,"/");
		setcookie("_token",$_token,time()*$stime,"/");
		setcookie("_uid",$uid,time()*$stime,"/");
		return true;
	} else {
		return false;
	}
}
function _mod($id,$func) {
	$user = info_user("id",$id);
	if(isset($user['id'])) {
		if(find_array($user["_mod"],$func) or $user['_lv'] >=10) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}
function getFolderPath($folderId) {
    GLOBAL $ketnoi;
    $path = '';

    while ($folderId != 0) {
        $sql = "SELECT id, _byid, _name FROM folders WHERE id = $folderId and _status='active'";
        $result = $ketnoi->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $path = '<svg height="16" width="14"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M278.6 233.4c12.5 12.5 12.5 32.8 0 45.3l-160 160c-12.5 12.5-32.8 12.5-45.3 0s-12.5-32.8 0-45.3L210.7 256 73.4 118.6c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0l160 160z"/></svg><span class="folders-path" onclick="_openFolders('.$row['id'].')">'.htmlspecialchars($row['_name']). '</span> ' . $path;
            $folderId = $row['_byid'];
        } else {
            break;
        }
    }

    return '<span class="folders-path" onclick=" window.location=\'/\'">Home</span>'.$path;
}