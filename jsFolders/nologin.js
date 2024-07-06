function login(txt,el) {
	if(txt == 'open') {
		_windows("ĐĂNG NHẬP",`
			<div class="form-login form">
			<label for="user">Tài khoản:</label>
			<input id="user">
			<label for="pass">Mật khẩu:</label>
			<input id="pass" type="password">
			<button class="btn center w-btn" onclick="login('go',this);">Đăng Nhập</button>

			</div>
			`,300);
	}
	if(txt == "go") {
		$(el).html(`<img src="/images/loading1.gif"> Đang đăng nhập...`);
		$(el).css('filter','grayscale(1)');
		$(el).attr('disabled', true); 
		form = $(el).closest('.form-login')
		user = form.find("#user");
		pass = form.find("#pass");
		$.ajax({
			method: "POST",
			url: "/response.php",
			data: {act: "login", user: user.val(),pass: pass.val()},
			success: function(response) {
				//console.log(response);
				
				res = $.parseJSON(response);
				if(res.res == 'false') {
					_windows('Đăng nhập thất bại',``+res.content+``,500);
					$(el).attr('disabled', false);
					$(el).removeAttr("style");
					$(el).html('Đăng Nhập');
				} else if(res.res == 'true') {
					_windows("Đăng Nhập Thành Công",`Chào mừng <b style="color:red">`+user.val()+`</b>, bạn đã đăng nhập thành công. Vui lòng làm mới trang để cập nhật hệ thống.<br><br><a href="" class="btn center w-btn">Làm Mới Ngay</a>`,300);
					form.closest("._view").fadeOut();
				}
			}
		});
	}
}
function reg(txt,el) {
	if(txt == 'open') {
		_windows("ĐĂNG KÝ",`
			<div class="form-reg form">
			<label for="user">Tài khoản:</label>
			<input id="user">
			<label for="pass">Mật khẩu:</label>
			<input id="pass" type="password">
			<label for="repass">Nhập lại mật khẩu:</label>
			<input id="repass" type="password">
			<label for="email">Email:</label>
			<input id="email" type="email">
			<label for="captcha">Trả lời phép tính sau:</label>
			<div class="group-input">
			<img alt="captcha" src="/images/captcha.php"><input id="captcha">
			</div>
			<button class="btn center w-btn" onclick="reg('go',this);">Đăng Ký</button>
			
			</div>
			`,300);
	}
	if(txt == 'go') {

		//_alert("Đang kiểm tra thông tin.");
		$(el).html(`<img src="/images/loading1.gif"> Đang đăng ký...`);
		$(el).css('filter','grayscale(1)');
		$(el).attr('disabled', true); 
		form = $(el).closest('.form-reg')
		user = form.find("#user");
		pass = form.find("#pass");
		repass = form.find("#repass");
		email = form.find("#email");
		captcha = form.find("#captcha");
		
		$.ajax({
			method: "POST",
			url: "/response.php",
			data: {act: "reg", user: user.val(),pass: pass.val(),repass: repass.val(),email: email.val(),captcha: captcha.val()},
			success: function(response) {
				res = $.parseJSON(response);
				if(res.res == 'false') {
					form.find("img[alt=captcha]").attr("src","/images/captcha.php?"+ new Date().getTime());
					_windows('Đăng ký thất bại',``+res.content+``,500);
					$(el).attr('disabled', false);
					$(el).removeAttr("style");
					$(el).html('Đăng Ký');


				} else if(res.res == 'true') {
					form.find("img[alt=captcha]").attr("src","/images/captcha.php?"+ new Date().getTime());
					_windows("Đăng Ký Thành Công",`Chào mừng <b style="color:red">`+user.val()+`</b> đã tham gia với chúng tôi.<br><br><button class="btn center w-btn" onclick="login('open');close_windows(this);">Đăng Nhập Ngay</button>`,300);
					form.closest("._view").fadeOut();
				}
			}
		});
	}
}
