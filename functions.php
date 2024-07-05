<?php

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);

date_default_timezone_set('Asia/Ho_Chi_Minh');
$time = time();
define("_root_", $_SERVER["DOCUMENT_ROOT"]);
define('_get_path_', isset($_GET['folders']) ? $_GET['folders'] : '0');
define("_video_", array("video/mp4","video/quicktime","video/3gpp"));
define("_image_", array("image/png","image/gif","image/jpeg","image/jpeg"));
$root = $_SERVER["DOCUMENT_ROOT"];
function _m5($txt,$txt2) {
    if(md5($txt) == md5($txt2)) {
        return true;
    } else {
        return false;
    }
}
function remove_dir($dir = null) {
    if (is_dir($dir)) {
        $objects = scandir($dir);
        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
                if (filetype($dir."/".$object) == "dir") remove_dir($dir."/".$object);
                else unlink($dir."/".$object);
            }
        }
        reset($objects);
        rmdir($dir);
    }
}
function check_string_user($str='x') {
    if (preg_match('/^[A-Za-z0-9]{2,31}$/', $str)) {
        return true;
    } else {
        return false;
    }
}
function checkmail($txt){
    $partten = "/^[A-Za-z0-9_.]{1,32}@([a-zA-Z0-9]{1,12})(.[a-zA-Z]{1,12})+$/";
    $subject = $txt;
    if(!preg_match($partten ,$subject, $matchs)) {
        return true;
    } else {
        return false;
    }
}
function bodau($str){
    $unicode = array(
        'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
        'd'=>'đ',
        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
        'i'=>'í|ì|ỉ|ĩ|ị',
        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
        'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
        'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
        'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
        'D'=>'Đ',
        'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
        'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
        'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
        'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
        'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ' '=> '_',
    );
    foreach($unicode as $nonUnicode=>$uni){
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    $str = str_replace(' ','_',$str);
    return $str;
}
function ktdb($string) {
    $replace_cham = str_replace('.', 'dauchamkaka', $string);
    $string0 = str_replace(' ', '-', $replace_cham);
    $string1 = preg_replace('/[^A-Za-z0-9_]/', '', $string0);
    return str_replace('dauchamkaka', '.', $string1);
}
function _device($str) {
    switch ($str) {
        case 'name':
        return  $_SERVER ['HTTP_USER_AGENT'];
        break;
        case 'ip':
        return  $_SERVER ['REMOTE_ADDR'];
        break;
        
        default:
        return "Get Info Device...";
        break;
    }
}
function rutgon($txt,$maxstring=16,$maxtext=10) {
    $zarray = explode(' ',$txt);
    $string = null;
    for($i=0;$i <= $maxstring;$i++) {
        if(!isset($zarray[$i])){
            $zarray[$i] = null;
        }
        if(strlen($zarray[$i]) >= $maxtext){
            $cuttxt = mb_substr($zarray[$i], 0,$maxtext);
            $zarray[$i] = $cuttxt.'...';
        }
        $string .= $zarray[$i].' ';
    }
    if(strlen($txt) > $maxstring) {
        $string .= "<div class='hide'></div>";
    }

    return $string;
}

function text_page($text,$number=10,$line=0){
    $arr = explode("\n", $text);
    $string = null;
    for($i=$line; $i < $line+$number;$i++) {
        if(!isset($arr[$i])) {
            $arr[$i] = null;
        } else {
            $arr[$i] = $arr[$i];

        }
        $string .= $arr[$i];
    }

    return $string;
}

function n_line($text) {
    $arr = explode("\n", $text);
    return count($arr)-1;
}
function d_line($text) {
    $replace = str_replace("\n", ' ',$text);
    echo $replace;
}

function find_array($array, $text) {
    $c_array = explode(',', $array);
    if (in_array($text, $c_array)) {
        return true;
    } else {
        return false;
    }
}
function _clrand1() {
    $letters = '0123456789ABCDEF';
    $color = '#';
    for ($i = 0; $i < 6; $i++) {
        $color .= $letters[rand(0, 15)];
    }
    return $color;
}
function _clrand2($color) {
    $r = hexdec(substr($color, 1, 2));
    $g = hexdec(substr($color, 3, 2));
    $b = hexdec(substr($color, 5, 2));
    $contrast = ($r * 299 + $g * 587 + $b * 114) / 1000;
    return $contrast >= 128 ? '#000000' : '#FFFFFF';
}
function _clconvert($color) {
    $r = hexdec(substr($color, 1, 2));
    $g = hexdec(substr($color, 3, 2));
    $b = hexdec(substr($color, 5, 2));
    $contrastR = 255 - $r;
    $contrastG = 255 - $g;
    $contrastB = 255 - $b;
    $contrastColor = sprintf("#%02X%02X%02X", $contrastR, $contrastG, $contrastB);

    return $contrastColor;
}

function compressImage($imageData, $quality) {
    $img = imagecreatefromstring($imageData);
    ob_start();
    imagejpeg($img, null, $quality);
    $compressedImageData = ob_get_clean();
    imagedestroy($img);
    return $compressedImageData;
}
?>