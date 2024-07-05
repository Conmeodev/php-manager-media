<?php
include_once 'set.php';
$title = "No Dead Link";
if(isset($_GET["file"])) {
	$title = info_file("id",$_GET["file"])["_name"];
} else {
	if(isset($_GET["folders"])) {
		$title = info_folders("id",$_GET["folders"])["_name"];
	}
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $title;?></title>
	<link rel="stylesheet" href="/style.css?v=<?php echo time();?>">
	<link rel="icon" href="/images/ico.png" type="image/x-icon"/>

</head>
<body>

	<div id="top">
		
		<div id="main" class="wrapper">
			<div id="left-main">
				<div class="pad2" style="position: sticky; top: 0; z-index: 1; overflow: scroll; height: 100vh; padding-bottom: 35px;">
					
					<div class="title">Tùy Chọn</div>
					<div id="nav_folders">
						<a class="nav_path_folders display" href="/"><span class="more_folders" style=" background: #2196f3; color: white; ">♥</span>
							<span>Trang Chủ</span>
						</a>
						<?php
						if(_login()) {
							echo '<div class="nav_path_folders" onclick="logout()"><span class="more_folders" style=" background: #f44336; color: white; ">❖</span>
							<span>Đăng Xuất</span>
							</div>

							<div class="nav_path_folders" onclick="changePass()"><span class="more_folders" style=" background: #ff9800; color: white; ">★</span>
							<span>Đổi mật khẩu</span>
							</div>';
						}
						?>
						<?php
						if(!_login()) {
							echo '<div class="nav_path_folders" onclick="reg(\'open\')"><span class="more_folders" style=" background: #8bc34a; color: white; ">۩</span>
							<span>Đăng Ký</span>
							</div>
							<div class="nav_path_folders" onclick="login(\'open\')"><span class="more_folders" style=" background: #ff9800; color: white; ">☊</span>
							<span>Đăng Nhập</span>
							</div>
							';
						}
						?>
						<?php
						if(_mod($uid,"upload")) {
							echo '<div class="nav_path_folders" onclick="create(`open_popup`);"><span class="more_folders" style=" background: #4caf50; color: white; ">+</span>
							<span>Thư Mục & Tải Lên</span>
							</div>';
						}
						?>
						<div class="title" onclick="_openFolders('0');">Danh Mục Chính</div>
						<?php echo file_get_contents(_domain_."/call.php?act=nav_folders&id=0");?>
						
					</div>
				</div>
			</div>
			<span class="mobile showLeftMenu" onclick="showLeftMenu();">✿</span>
			<div id="right-main">
				<div class="pad2" >
					<div class="head">
						<?php if(_get_path_ <=0) { ?>
							<div class="svg"><svg height="16" width="14"  xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512"><!--!Font Awesome Free 6.5.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2024 Fonticons, Inc.--><path d="M16 144a144 144 0 1 1 288 0A144 144 0 1 1 16 144zM160 80c8.8 0 16-7.2 16-16s-7.2-16-16-16c-53 0-96 43-96 96c0 8.8 7.2 16 16 16s16-7.2 16-16c0-35.3 28.7-64 64-64zM128 480V317.1c10.4 1.9 21.1 2.9 32 2.9s21.6-1 32-2.9V480c0 17.7-14.3 32-32 32s-32-14.3-32-32z"/></svg></div>
							<div class="logo"><a href="/"><?php echo $_domain;?></a></div>
						<?php } else {
							echo getFolderPath($_GET['folders']);
						}?>
					</div>
				</div>
				<div class="pad2">
					<div class="title">Thư Mục</div>
					<div id="list_folders">
						<?php echo file_get_contents(_domain_."/call.php?act=call_folders&type=folders&id="._get_path_);?>
					</div>

					<div class="title">Tập Tin</div>
					<div id="list_files">
						<?php echo file_get_contents(_domain_."/call.php?act=call_folders&type=files&id="._get_path_);?>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	<script src="/main.js?v=<?php echo time();?>"></script>
	
	


</body>
</html>