<?php

ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL);
$DOCUMENT_ROOT = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : "C:/xampp/htdoc";
$root = $DOCUMENT_ROOT.'/';
$host = isset($_POST['host']) ? $_POST['host'] : null;
$user = isset($_POST['user']) ? $_POST['user'] : null;
$pass = isset($_POST['pass']) ? $_POST['pass'] : "";
$name = isset($_POST['name']) ? $_POST['name'] : null;

if(isset($host)) {
	$ketnoi = mysqli_connect($host,$user,$pass,$name);
	if($ketnoi) {
		$config_file = fopen($root.'/_config.php', 'w+');
		fwrite($config_file, "<?php\n\$db_host = '$host';\n\$db_user = '$user';\n\$db_pass = '$pass';\n\$db_name = '$name';\n?>");
		fclose($config_file);


		$sql = "SHOW TABLES";
		$result = mysqli_query($ketnoi, $sql);
		while ($row = mysqli_fetch_row($result)) {
			$tableName = $row[0];

			$deleteTableSql = "DROP TABLE $tableName";
			$deleteResult = mysqli_query($ketnoi, $deleteTableSql);
		}





		$tempLine = '';
		$lines = file("upload.sql");
		foreach ($lines as $line) {
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$tempLine .= $line;
			if (substr(trim($line), -1, 1) == ';')  {
				mysqli_query($ketnoi, $tempLine) or print("Error in " . $tempLine .":". mysqli_error());
				$tempLine = '';
			}
		}
		echo 'Cài đặt thành công. Vui lòng xóa thư mục <span style="color:red">install</span> để tránh rủi ro.<br><a href="/">Xem trang web</a>';

	} else {
		echo "Thông tin database không đúng.";
	}
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Install Manager Media</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="/style.css?v=<?php echo time();?>">
	<link rel="icon" href="/images/ico.png" type="image/x-icon"/>
</head>
<body>
	<div class="_view" id="popup1001" style="display: flex; opacity: 1;">
		<div class="v_container" style="max-width: 300px; opacity: 1; transform: scale(1);">
			<div class="v_header">
				<div class="v_close"><img src="/images/ico.png" style="max-width: 24px;"></div>
				<span class="vh_name">Install Manager Media</span>
			</div>
			<div class="v_body">
				<span style="color:red">* Lưu ý:</span> Vui lòng tạo database trước khi cài đặt. Thao tác cài đặt sẽ xóa tất cả dữ liệu trong database.
				<div class="form-install form">
					<label for="host">Database Host:</label>
					<input id="host" value="localhost">
					<label for="user">Username:</label>
					<input id="user" placeholder="Username...">
					<label for="pass">Password:</label>
					<input id="pass" placeholder="Password...">
					<label for="name">Database Name:</label>
					<input id="name" placeholder="Database Name...">

					<button class="btn center w-btn" id="btn" onclick="install();">Check and Install</button>

				</div>

			</div>
		</div>
	</div>
</body>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script><?php include_once $root.'/jsFolders/main.js'; ?></script>

<script>
	function install(){
		var host = $("#host"),
		user = $("#user"),
		pass = $("#pass"),
		name = $("#name"),
		btn = $("#btn");
		btn.attr('disabled', true);
		btn.css({'background':'var(--border)','color':'var(--color)'});
		_alert("Đang kiểm tra kết nối và cài đặt CSDL...",30000000,"wait");

		$.ajax({
			method: "POST",
			data: {host:host.val(),user:user.val(),pass:pass.val(),name:name.val()},
			success: function(res){
				$("#alert").remove();
				btn.attr('disabled', false);
				btn.attr("style","");
				_box_alert(res);
			}
		});
	}
</script>
</html>
