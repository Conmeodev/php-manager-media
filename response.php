<?php
include_once "set.php";

$act = isset($_POST["act"]) ? $_POST["act"] : null;
$title = $_domain;
$res = "false";
$content = null;
if(_m5($act,'rename_folders')) {
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if(_folders($id)["_uid"] == $uid) {
        $name1 = _sql($name);
        $update = _query("UPDATE folders SET _name='$name1' WHERE id='$id' and _status='active'");
        if($update) {
            $res = "true";
            $content = "Thay đổi tên thư mục thành công.";
        } else {
            $content = "Đã xảy ra lỗi khi thay đổi tên thư mục.";
        }
    } else {
        $content = "Bạn không có quyền sửa đổi thư mục này.";
    }
}
if(_m5($act,'rename_files')) {
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if(_file($id)["_uid"] == $uid) {
        $name1 = _sql($name);
        $update = _query("UPDATE file SET _name='$name1' WHERE id='$id' and _status='active'");
        if($update) {
            $res = "true";
            $content = "Thay đổi tên tập tin thành công.";
        } else {
            $content = "Đã xảy ra lỗi khi thay đổi tên tập tin.";
        }
    } else {
        $content = "Bạn không có quyền sửa đổi tập tin này.";
    }
}
if(_m5($act,'delete_folders')) {
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if(_folders($id)["_uid"] == $uid) {
        $update = _query("UPDATE folders SET _status='delete' WHERE id='$id'");
        $updatef = _query("UPDATE file SET _status='delete' WHERE byid='$id'");
        if($update) {
            $res = "true";
            $content = "Xóa thư mục thành công.";
        } else {
            $content = "Đã xảy ra lỗi khi xóa thư mục.";
        }
    } else {
        $content = "Bạn không có quyền xóa thư mục này.";
    }
}
if(_m5($act,'delete_files')) {
    $id = isset($_POST["id"]) ? $_POST["id"] : null;
    if(_file($id)["_uid"] == $uid) {
        $update = _query("UPDATE file SET _status='delete' WHERE id='$id' and _status='active'");
        if($update) {
            $res = "true";
            $content = "Xóa tập tin thành công.";
        } else {
            $content = "Đã xảy ra lỗi khi xóa tập tin.";
        }
    } else {
        $content = "Bạn không có quyền xóa tập tin này.";
    }
}
if(_m5($act,'create_folders')) {
    $_POST_path = isset($_POST["path"]) ? $_POST["path"] : null;
    $name = isset($_POST["name"]) ? $_POST["name"] : null;
    $byid = isset($_POST["_byid"]) ? $_POST["_byid"] : 0;
    $token = md5($name.$time.$byid);

    if(!_mod($uid, "create_folders")) {
        $content .= "<li>Bạn không có quyền thực hiện tính năng này.</li>";
    }

    if(!isset($content)) {
        $insert = _query("INSERT INTO folders(_uid,_name,_timecreate,_token,_byid,_status) VALUES('$uid','$name','$time','$token','$byid','active')");
        if($insert) {
            $res = "true";
            $content .= "Tạo thư mục thành công. $name";
        } else {
            $content .= "Tạo thư mục thất bại.";
        }
    }
}
if (_m5($act, "load_thumb")) {
    $_POST_path = isset($_POST["path"]) ? $_POST["path"] : null;
    if (isset(info_file("id", $_POST_path)["id"])) {
        return info_file("id", $_POST_path)["thumb"];
    } else {
        return "/_asset/ghost_load.gif";
    }
}
if (_m5($act, "upload")) {
    if ($_FILES["file"]["error"] === UPLOAD_ERR_OK && _mod($uid, "upload")) {
        $fileName = $_FILES["file"]["name"];
        $fileSize = $_FILES["file"]["size"];
        $fileType = $_FILES["file"]["type"];
        $duoi = explode("/",$fileType)[1];
        $new_name =   time() . "_" . md5($fileName).'.'.$duoi;
        $filePath = "uploads/".$new_name;
        $folders = isset($_POST["_byid"]) ? $_POST["_byid"] : "0";
        if($folders == null) {$folders = 0;}
        $token = md5($filePath);

        if (move_uploaded_file($_FILES["file"]["tmp_name"], $filePath)) {
            $insert = _query(
                "INSERT INTO file(_name,_uid,_byid,_time,_share,_token,old_name,_type,_dir,_status,_size) VALUES('$fileName','$uid','$folders','$time','public','$token','$fileName','$fileType','$new_name','active','$fileSize')"
            );
            if ($insert) {
                $content .= "Tên file: $fileName - Kích thước: $fileSize bytes - Định dạng: $fileType - Đường dẫn: $filePath";
            } else {
                $content .=
                "Có lỗi xảy ra khi insert thông tin file vào database.";
            }
        } else {
            $content .= "Có lỗi xảy ra khi tải lên file.";
        }
    } else {
        $content .= "Bạn không có quyền thực hiện thao tác này.";
    }
}
if (_m5($act, "login")) {
    if (_login()) {
        $content = "<li>Bạn đã đăng nhập rồi</li>";
    } else {
        $user = isset($_POST["user"]) ? $_POST["user"] : null;
        $pass = isset($_POST["pass"]) ? $_POST["pass"] : null;
        $ck_user = info_user("_user", $user);
        if (!isset($ck_user["_user"])) {
            $content .= "<li>Tài khoản không tồn tại.</li>";
        }
        if (!_m5($pass, $ck_user["_showpass"])) {
            $content .= "<li>Mật khẩu không chính xác.</li>";
        }
        if ($content == null) {
            $time_login = 99999999;
            $_txt =
            $user .
            "|--|" .
            _device("name") .
            "|--|" .
            _device("ip") .
            "|--|" .
            date("H:i:s | d-m-Y");
            $_token = md5($_txt);
            $_device = md5($_token . _device("name") . time());

            $insert = _query(
                "INSERT INTO login(uid,_time,_token,_status,_content,_device) VALUES('" .
                info_user("_user", $user)["id"] .
                "','" .
                time() .
                "','$_token','login','$_txt','$_device')"
            );
            setcookie("_device", $_device, time() + $time_login, "");
            setcookie("_token", $_token, time() + $time_login, "");
            setcookie(
                "_uid",
                info_user("_user", $user)["id"],
                time() + $time_login,
                ""
            );
            $res = "true";
            $content .= "Đăng nhập thành công";
        }
    }
} else if (_m5($act, "reg")) {
    if (_login()) {
        $content = "Bạn đang đăng nhập.";
    } else {
        $user = isset($_POST["user"]) ? $_POST["user"] : null;
        $pass = isset($_POST["pass"]) ? $_POST["pass"] : null;
        $repass = isset($_POST["repass"]) ? $_POST["repass"] : null;
        $email = isset($_POST["email"]) ? $_POST["email"] : null;
        $captcha = isset($_POST["captcha"]) ? $_POST["captcha"] : null;

        if (!_m5($captcha, $_SESSION["captcha_result"])) {
            $content .= "<li>Error 1: Vui lòng nhập đúng kết quả.</li>";
        }
        if (!check_string_user($user)) {
            $content .=
            "<li>Error 2: Tên tài khoản không được chứa kí tự đặc biệt.</li>";
        }
        if (strlen($user) > 15) {
            $content .=
            "<li>Error 3: Tên đăng nhập không được vượt quá 15 ký tự.</li>";
        }
        if ($user == "" or $pass == "" or $repass == "" or $email == "") {
            $content .= "<li>Error 4:Không được để trống các ô thông tin.</li>";
        }
        if (checkmail($email)) {
            $content .= "<li>Error 5:Định dạng email không đúng.</li>";
        }
        if (!_m5($pass, $repass)) {
            $content .=
            "<li>Error 6:Hai mật khẩu không khớp nhau, vui lòng kiểm tra lại.</li>";
        }
        if (!isset($content)) {
            $check_user = info_user("_user", $user);
            $check_email = info_user("_email", $email);
            if ($check_user) {
                $content .= "<li>Error 7: Tài khoản này đã tồn tại.</li>";
            }
            if ($check_email) {
                $content .= "<li>Error 8: Email này đã tồn tại.</li>";
            }
            if (!isset($content)) {
                $_user = _sql($user);
                $_pass = md5($pass);
                $_spass = _sql($pass);
                $_email = _sql($email);
                $_token = md5($user . $pass . $email . time());

                $txt_in = "INSERT INTO user(_user,_pass,_email,_lv,_timecreater,_token,_showpass,_share) VALUES('$_user','$_pass','$_email','0','$time','$_token','$_spass','public')";
                $insert = _query($txt_in);
                if ($insert) {
                    $res = "true";
                    $content .=
                    "<li>Đăng ký thành công. Chuyển tới trang đăng nhập.</li>";
                }
            }
        }
    }
} 
if (_m5($act, "user_change_pass")) {
    if (_login()) {
        $newpass = isset($_POST["newpass"]) ? $_POST["newpass"] : null;
        $old_pass = isset($_POST["oldpass"]) ? $_POST["oldpass"] : null;

        if(md5($old_pass) == $zuser["_pass"]) {
            $new_pass = _sql($newpass);
            $md5_newpass = md5($newpass);
            $update = _query("UPDATE user SET _pass='$md5_newpass', _showpass='$new_pass' WHERE id='$uid'");
            $content = "<li>Đổi mật khẩu thành công.</li>";
        } else {
            $content = "<li>Mật khẩu cũ không đúng.</li>";
        }
    } else {
       $content = "<li>Không tìm thấy yêu cầu.</li>";
   }
}
$return = ["res" => $res, "title" => $title, "content" => $content];
echo json_encode($return);
?>
