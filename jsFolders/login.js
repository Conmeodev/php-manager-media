function changePass(str="form"){
	if(str=="form") {
		_windows(`Đổi mật khẩu`,`
			<div class="form-change-pass form">
			<label for="old_pass">Mật khẩu cũ:</label>
			<input id="old_pass" type="password">
			<label for="input_new_pass">Mật khẩu mới:</label>
			<input id="input_new_pass" type="text" autocomplete=false>
			<button class="btn center w-btn" onclick="changePass('go');">Đổi Ngay</button>

			</div>
			`,400);
	}
	else if(str=="go") {
		var newpass = $("#input_new_pass");
		var oldpass = $("#old_pass");
		$.ajax({
			method: "POST",
			url: "/response.php",
			data: {act:"user_change_pass",oldpass: oldpass.val(),newpass:newpass.val()},
			success: function(response){
				res = $.parseJSON(response);
				_windows("Thông báo",res.content,400);
			}
		});
	}
}


function logout() {
	_windows("Đăng Xuất", "Đang đăng xuất...", 300);
	$.ajax({
		url: "/",
		method: "GET",
		data: {
			logout: "as"
		},
		success: function(response) {
			console.log(response);
			_windows("Đăng Xuất", 'Đăng Xuất Thành Công <a href="" class="btn center w-btn">Làm Mới Trang</a>', 300);
		}
	});
}

function create(act, el) {
	if (act == 'go_create_folders') {
		form = $(el).closest('.form-create-folders');
		$(el).html(`<img src="/images/loading1.gif"> Đang tạo thư mục...`);
		$(el).css('filter', 'grayscale(1)');
		$(el).attr('disabled', true);
		name_folders = form.find("#input_name_folders");
		_name = name_folders.val();
		_byid = _get("folders");
		$.ajax({
			url: "/response.php",
			method: "POST",
			data: {
				act: "create_folders",
				name: _name,
				_byid: _byid
			},
			success: function(response) {
				res = $.parseJSON(response);
				if (res.res == 'false') {
					_alert(res.content);
					$(el).html(`Tạo thư mục`);
					$(el).css('filter', 'unset');
					$(el).attr('disabled', false);
				}
				if (res.res == 'true') {
					_alert("Tạo thư mục thành công", 3000, "success");
					location.reload();
				}
			}
		});
	}
	if (act == 'new_folders') {
		_windows('Tạo thư mục', `
			<div class="form-create-folders form">
			<label for="input_name_folders">Tên thư mục mới:</label>
			<input id="input_name_folders">
			<button class="btn center w-btn" onclick="create('go_create_folders',this);">Tạo thư mục</button>
			
			</div>
			`, 400);
	}
	if (act == "open_popup") {
		_windows("Tùy Chọn", `
			<button onclick="create('new_folders')">Tạo Thư Mục Mới</button>
			<br>
			<br>
			<br>
			<div class="drop-section">
			<div class="col">
			<div class="cloud-icon">
			<img src="/asset/image/icons/cloud.png" alt="cloud">
			</div>
			<span>Kéo và thả tệp vào đây</span>
			<span>Hoặc</span>
			<button class="file-selector">Chọn Tệp Từ Máy</button>
			<input type="file" class="file-selector-input" multiple>
			</div>
			<div class="col">
			<div class="drop-here">Thả Vào</div>
			</div>
			</div>
			<div class="list-section">
			<div class="list-title">Uploaded Files</div>
			<div class="list"></div>
			</div>
			`, 400, function() {
				var _byid = _get("folders");
				if (_byid == "" || _byid == null) {var _byid=0;}
				const dropArea = document.querySelector('.drop-section');
				const listSection = document.querySelector('.list-section');
				const listContainer = document.querySelector('.list');
				const fileSelector = document.querySelector('.file-selector');
				const fileSelectorInput = document.querySelector('.file-selector-input');

				fileSelector.onclick = () => fileSelectorInput.click();
				fileSelectorInput.onchange = () => {
					[...fileSelectorInput.files].forEach((file) => {
						if (typeValidation(file.type)) {
							uploadFile(file);
						}
					});
				};

				dropArea.ondragover = (e) => {
					e.preventDefault();
					dropArea.classList.add('drag-over-effect');
				};
				dropArea.ondragleave = () => {
					dropArea.classList.remove('drag-over-effect');
				};
				dropArea.ondrop = (e) => {
					e.preventDefault();
					dropArea.classList.remove('drag-over-effect');
					if (e.dataTransfer.items) {
						[...e.dataTransfer.items].forEach((item) => {
							if (item.kind === 'file') {
								const file = item.getAsFile();
								if (typeValidation(file.type)) {
									uploadFile(file);
								}
							}
						});
					} else {
						[...e.dataTransfer.files].forEach((file) => {
							if (typeValidation(file.type)) {
								uploadFile(file);
							}
						});
					}
				};

				function typeValidation(type) {
					const splitType = type.split('/')[0];
					return splitType === 'image' || splitType === 'video';
				}

				function uploadFile(file) {
					listSection.style.display = 'block';
					const li = document.createElement('li');
					li.classList.add('in-prog');
					li.innerHTML = `
					<div class="col">
					<img src="/asset/image/icons/${iconSelector(file.type)}" alt="">
					</div>
					<div class="col">
					<div class="file-name">
					<div class="name">${file.name}</div>
					<span class="progress">0%</span>
					</div>
					<div class="file-progress">
					<span class="progress-bar" style="width: 0%"></span>
					</div>
					<div class="file-size">${(file.size / (1024 * 1024)).toFixed(2)} MB</div>
					</div>
					<div class="col">
					<svg xmlns="http://www.w3.org/2000/svg" class="cross" height="20" width="20"><path d="m5.979 14.917-.854-.896 4-4.021-4-4.062.854-.896 4.042 4.062 4-4.062.854.896-4 4.062 4 4.021-.854.896-4-4.063Z"/></svg>
					<svg xmlns="http://www.w3.org/2000/svg" class="tick" height="20" width="20"><path d="m8.229 14.438-3.896-3.917 1.438-1.438 2.458 2.459 6-6L15.667 7Z"/></svg>
					</div>
					`;
					listContainer.prepend(li);

					const http = new XMLHttpRequest();
					const data = new FormData();
					data.append('file', file);
					data.append('act', 'upload');
					data.append('_byid', _byid);

					http.onload = () => {
						li.classList.add('complete');
						li.classList.remove('in-prog');
					};

					http.upload.onprogress = (e) => {
						console.log('Server Response:', http.responseText);
						if (e.lengthComputable) {

							const percentComplete = (e.loaded / e.total) * 100;
							li.querySelector('.progress').textContent = `${Math.round(percentComplete)}%`;
							li.querySelector('.progress-bar').style.width = `${percentComplete}%`;
						}
					};

					http.open('POST', '/response.php', true);
					http.send(data);

					li.querySelector('.cross').onclick = () => {
						http.abort();
						li.remove();
					};

					http.onabort = () => li.remove();
				}

				function iconSelector(type) {
					const splitType = (type.split('/')[0] === 'application') ? type.split('/')[1] : type.split('/')[0];
					return splitType + '.png';
				}

			});
}
}
