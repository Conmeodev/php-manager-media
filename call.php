<?php
include_once 'set.php';
$id = isset($_GET["id"]) ? $_GET["id"] : null;
$act = isset($_GET['act']) ? $_GET['act'] : null;
$type = isset($_GET["type"]) ? $_GET["type"] : null;

if(_m5($act, "get_folders")) {
    $folders = _fetch("SELECT * FROM folders WHERE id='$id' and _status='active'");
    $folders["_time"] = date("H:i:s - d/m/Y",$folders["_timecreate"]);
    echo json_encode($folders);
}
if(_m5($act, "get_file")) {
    $folders = _fetch("SELECT * FROM file WHERE id='$id' and _status='active'");
    $folders["_time"] = date("H:i:s - d/m/Y",$folders["_timecreate"]);
    echo json_encode($folders);
}
if(_m5($act, "folders_path")) {
    echo getFolderPath($id);
}

if (_m5($act, "load_thumb")) {
    $type = _file($id)['_type'];
    if(in_array($type,_image_)) {
        echo '/pictures/thumb/'._file($id)['_dir'];
    }
}
if(_m5($act, "nav_folders")) {
    $id = isset($id) ? $id : 0;
    $txt = _query("SELECT * FROM folders WHERE _byid='$id' and _status='active'");
    if($id != "0") {
        echo '<div class="nav_path_folders"><span class="more_folders">-</span> <span>...</span></div>';
    }
        while ($list = w_fetch($txt)) {
            if($id == "0") {echo '<div class="public_folders">';}
            $clrand1 = _clrand1();
            $clrand2 = _clrand2($clrand1);
             echo '<div class="nav_path_folders">';
             if($id == "0") {echo '<span style="background-color:'.$clrand1.';color:'.$clrand2.'" class="more_folders" onclick="show_nav_folders(\''.$list["id"].'\',this)">+</span>';}
             else{echo '<span class="more_folders" onclick="show_nav_folders(\''.$list["id"].'\',this)">+</span>';}
             echo ' <span onclick="_openFolders(\''.$list["id"].'\');nav_call_folders(\''.$list["id"].'\',this);showLeftMenu();">'.htmlspecialchars($list["_name"]).'</span></div>';
             if($id == "0") {echo '</div>';}
        }
}


if (_m5($act, "call_folders")) {
    if (_m5($type, "folders")) {
        $txt = _query("SELECT * FROM folders WHERE _byid='$id' and _status='active'");
        while ($list = w_fetch($txt)) {
            echo _template($list['id'],"folders");
        }
    } elseif (_m5($type, "files")) {
        $txt = _query("SELECT * FROM file WHERE _byid='$id' and _status='active'");
        while ($list = w_fetch($txt)) {
             echo _template($list['id'],"file");
        }
    } else {
        exit("Dữ liệu không chính xác");
    }
}
if (_m5($act, "file_info")){
    echo json_encode(_file($id));
}
if (_m5($act, "view_file")){
    $file = _file($id);
    if(in_array($file['_type'],_image_)) {
        echo '<div class="view"><img src="/uploads/'.$file["_dir"].'"></div>';
    }
    else if(in_array($file['_type'],_video_)) {
        echo '<div class="view"><video autoplay loop controls><source src="/uploads/'.$file["_dir"].'" type="video/mp4"></video></div>';
    }
}