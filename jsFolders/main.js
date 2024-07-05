var _video_ = ["video", "video/mp4", "video/quicktime", "video/3gpp"];
var _image_ = ["image", "image/png", "image/gif", "image/jpeg"];





$(document).ready(function() {
    var file = _get("file");
    if (file == "" || !file || file == null || file == undefined) {} else {_view(file);} 
	$(window).on('popstate', function(event) {
		var folders = _get("folders");
		if (folders == "" || !folders || folders == null || folders == undefined) {
			folders = 0;
		}
		_openFolders(folders);
		
	});
});

function check_trans(imageUrl) {
  return new Promise(async (resolve, reject) => {
    try {
      const response = await $.ajax({
        url: imageUrl,
        method: 'GET',
        xhrFields: {
          responseType: 'blob'
        }
      });

      // Xử lý ảnh tải về
      const imageBlob = response.data;
      const isTransparent = await checkTransparent(imageBlob);
      resolve(isTransparent);
    } catch (error) {
      reject(false);
      console.error(error);
    }
  });
}
function _size(size) {
    if (size < 1024) {
        return size + 'B';
    } else if (size < 1048576) {
        return (size / 1024).toFixed(2) + 'KB';
    } else if (size < 1073741824) {
        return (size / 1048576).toFixed(2) + 'MB';
    } else {
        return (size / 1073741824).toFixed(2) + 'GB';
    }
}
function checkTransparent(imageBlob) {
	var img = new Image();
	img.crossOrigin = 'Anonymous';

	return new Promise(function(resolve) {
		img.onload = function() {
			var canvas = $("<canvas>")[0];
			canvas.width = img.width;
			canvas.height = img.height;

			var ctx = canvas.getContext('2d');
			ctx.drawImage(img, 0, 0);

			var isTransparent = Array.from(ctx.getImageData(0, 0, img.width, img.height).data).some((value, index, array) => (index + 1) % 4 === 0 && value !== 255);

			resolve(isTransparent);
		};

		img.src = URL.createObjectURL(imageBlob);
	});
}


function inArray(value, array) {
	for (var i = 0; i < array.length; i++) {
		if (array[i] === value) {
			return true;
		}
	}
	return false;
}

function _open_file(el) {
    window.localStorage.setItem('previousTitle', document.title);
	var currentTime = new Date().getTime();
	var text = $(el).find(".name").text();
	//chu_chay($(el).find(".name"));

	var id = $(el).attr("_idFiles");
	_view(id);
}

function showLeftMenu() {
    if($("html").width() < 400) {
	$("#left-main").slideToggle();
    }
}

function _view(id) {
    change_get("file", id);
	$("._view").remove();
	_alert("", 200000, "load");
	$.ajax({
		method: "GET",
		url: "/call.php",
		data: {
			act: "file_info",
			id: id
		},
		success: function(response) {
		    res = $.parseJSON(response);
			$("#alert").fadeOut();
			var html =null;
			if(inArray(res['_type'],_video_)) {
			    html = `<div class="view"><video autoplay loop controls><source src="/uploads/`+res['_dir']+`" type="video/mp4"></video></div>`;
			} else if(inArray(res['_type'],_image_)) {
			    html = `<div class="view"><img src="/uploads/`+res['_dir']+`"></div>`;
			}
			_wdFiles('<a target="_blank" href="/uploads/'+res['_dir']+'">'+res['_name']+'</a>', html);
			document.title = res['_name'];
		}
	});
}

function chu_chay(element) {
	var text = $(element).text();
	$('.box .marquee-text').replaceWith(function() {
		return $(this).text();
	});
	if ($(element).hasClass('marquee-text')) {
		$(element).replaceWith(function() {
			return $(this).text();
		});
	} else {
		$(element).html('<marquee class="marquee-text">' + text + '</marquee>');
	}
	event.stopPropagation();
}
$(document).on('click', function() {
	$('.marquee-text').replaceWith(function() {
		return $(this).text();
	});
});

function _rand1a() {
	var randomString = Math.floor(Math.random() * 1000000).toString();
	var timestamp = new Date().getTime().toString();
	var result = randomString + timestamp;

	return result;
}
/*
function call_folders(path = "", type = "folders") {
    if (path != "") {
        $("#nav_folders").html(_path_nav(path));
        change_get("path", path);
    } else {
        $("#nav_folders").html("");
    }
    $("#list_" + type).slideToggle();
    $.ajax({
        url: "/response.php",
        method: "POST",
        data: {
            act: 'call_list',
            path: path,
            type: type
        },
        success: function(response) {
            $("#list_" + type).html(response).slideToggle();
            _load_thumb();

        }
    });
}
*/
_load_thumb();

function _load_thumb() {
	$(document).ready(function() {
		function handleImageThumbnail(_box, id_box, _bdir, _bsize) {
			if (_bsize > 1000000) {
				_box.find(".thumb").css("background-image", 'url(/images/loading1.gif)');
				var thumbnailName = id_box + _rand1a() + ".png";

				$.ajax({
					url: '/_save_thumbnail.php',
					type: 'POST',
					data: {
						act: "image",
						path: thumbnailName,
						data: _bdir,
						id: id_box
					},
					success: function(res) {
						_box.find(".thumb").css("background-image", 'url(/uploads/thumb/' + thumbnailName + ')');
						console.log(res);
					}
				});
			} else {
				_box.find(".thumb").css("background-image", "url(" + _bdir + ")");
			}
		}

		function handleVideoThumbnail(_box, idfile, _bdir) {
			_box.find(".thumb").css("background-image", 'url(/images/loading1.gif)');
			var thumbnailName = idfile + _rand1a() + ".png";

			var video = document.createElement("video");
			video.src = _bdir;
			video.onloadedmetadata = function() {
				if (video.videoWidth > 0 && video.videoHeight > 0) {
					var canvas = document.createElement("canvas");
					canvas.width = video.videoWidth;
					canvas.height = video.videoHeight;
					var ctx = canvas.getContext("2d");
					video.currentTime = 2;
					video.onseeked = function() {
						ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
						var imageData = canvas.toDataURL("image/png");

						if (imageData.indexOf("data:image/png") === 0) {
							$.ajax({
								url: '/_save_thumbnail.php',
								type: 'POST',
								data: {
									act: "video",
									path: thumbnailName,
									data: imageData,
									id: idfile
								},
								success: function(res) {
									var url_thumb = '/uploads/thumb/' + thumbnailName;
									if (!check_trans(url_thumb)) {
										console.log("Xử Lý lại: " + url_thumb);
										handleVideoThumbnail(_box, idfile, _bdir);
									} else {
										console.log("Hoàn tất: " + url_thumb);
									}
									_box.find(".thumb").css("background-image", 'url(/uploads/thumb/' + thumbnailName + ')');
								},
								error: function() {
									console.log("Lỗi khi lưu thumbnail!");
								}
							});
						} else {
							console.log("Dữ liệu hình ảnh không hợp lệ!");
						}
					};
				} else {
					console.log("Kích thước video không hợp lệ!");
				}
			};
		}

		function intersectionCallback(entries, observer) {
			entries.forEach(function(entry) {
				if (entry.isIntersecting) {
					var _box = $(entry.target);
					var _bthumb = _box.attr("_thumb");
					var _btype = _box.attr("_type");
					var _bdir = _box.attr("_dir");
					var _bsize = _box.attr("_size");
					var _btime = _box.attr("_time");
					var id_box = _box.attr("_idFiles");
					_box.find(".thumb").css({
						'background-image': 'url("/images/ghost_load.gif")'
					});

					var idfile;

					if (_bthumb === "") {
						if (inArray(_btype, _image_)) {
							handleImageThumbnail(_box, id_box, _bdir, _bsize);
						} else if (inArray(_btype, _video_)) {
							idfile = _box.attr("_idfiles");
							handleVideoThumbnail(_box, idfile, _bdir);
						}
					} else {
						_box.find(".thumb").css("background-image", "url(" + _bthumb + ")");

					}
					observer.unobserve(entry.target);
				}
			});
		}

		var observer = new IntersectionObserver(intersectionCallback, {
			root: null,
			threshold: 0.5
		});

		$('.box_files').each(function() {
			observer.observe(this);
		});
	});
}












function _alert(text, _time = 3000, css = "error") {
	$("#alert").remove();
	$("body").append("<div id='alert' class='" + css + "'>" + text + "</div>");
	$("#alert").fadeIn();
	setTimeout(function() {
		$("#alert").fadeOut(function() {
			$(this).remove();
		});
	}, _time);
}

function _box_alert(message, autoHideDelay = null) {
	var overlay = $('<div>').addClass('overlay');
	$('body').append(overlay);
	$('body').css('overflow', 'hidden');

	var boxAlertContainer = $('<div>').addClass('box-alert-container');
	var boxAlertContent = $('<div>').addClass('box-alert-content').html(message);
	var boxAlertButtons = $('<div>').addClass('box-alert-buttons');
	var closeButton = $('<button>')
		.addClass('box-alert-button')
		.text('Đóng')
		.on('click', function() {
			overlay.remove();
			$('body').css('overflow', 'auto');

			boxAlertContainer.remove();
		});
	boxAlertButtons.prepend(closeButton);
	boxAlertContainer.prepend(boxAlertContent, boxAlertButtons);
	$('body').prepend(boxAlertContainer);
	overlay.fadeIn();
	boxAlertContainer.fadeIn();

	if (autoHideDelay !== null) {
		setTimeout(function() {
			overlay.fadeOut(function() {
				$(this).remove();
			});
			boxAlertContainer.fadeOut(function() {
				$(this).remove();
				$('body').css('overflow', 'auto');
			});
		}, autoHideDelay);
	}
}

function del_get(getValue) {
    var urlParts = window.location.href.split("?");
    
    if (urlParts.length > 1) {
        var baseUrl = urlParts[0];
        var params = urlParts[1].split("&");
        var newParams = [];

        for (var i = 0; i < params.length; i++) {
            var pair = params[i].split("=");
            if (pair[0] !== getValue) {
                newParams.push(params[i]);
            }
        }

        var newUrl = baseUrl + (newParams.length > 0 ? "?" + newParams.join("&") : "");
        window.history.replaceState(null, null, newUrl);
    }
}

function change_get(_get, _value) {
	if (!_get || !_value) {
		return;
	}

	var currentUrl = window.location.href;
	var urlParts = currentUrl.split("?");
	var params = {};

	if (urlParts.length > 1) {
		var queryString = urlParts[1];
		var paramPairs = queryString.split("&");
		for (var i = 0; i < paramPairs.length; i++) {
			var pair = paramPairs[i].split("=");
			params[pair[0]] = pair[1];
		}
	}

	if (params.hasOwnProperty(_get)) {
		params[_get] = _value;
	} else {
		params[_get] = _value;
	}

	var newQueryString = Object.keys(params).map(function(key) {
		return key + "=" + params[key];
	}).join("&");

	var newUrl = urlParts[0] + "?" + newQueryString;
	history.pushState(null, null, newUrl);
}

function _get(key) {
	const url = new URL(window.location.href);
	const query = url.searchParams;
	return query.get(key);
}

function _explode(input, separator) {
	var segments = input.split(separator);
	return segments[segments.length - 1];
}

var zIndexCounter = 1000;
function closeFile() {
    del_get('file');
    $('.wd-file').remove();
    $('body').removeClass('hide-scroll');
    document.title = window.localStorage.getItem('previousTitle');
}
function _wdFiles(name,html) {
    zIndexCounter++;

	var popupId = `popup${zIndexCounter}`;
	$("#wd-files").remove();
	$("body").addClass("hide-scroll");
	zIndexCounter++;
	$("body").append(`
            <div class="wd-file" id="${popupId}">
            <div class="wdf-close" onclick="closeFile();">-</div>
            <div class="wdf-name">`+name+`</div>
            <div class="wdf-content">`+html+`</div>
            </div>
            `);

}

function _windows(title, code, width = 600, callback) {

	zIndexCounter++;

	var popupId = `popup${zIndexCounter}`;
	$("body").addClass("hide-scroll");
	$("body").prepend(`
        <div class="_view" id="${popupId}" style="z-index:${zIndexCounter}">
        <div class="v_container" style="max-width:${width}px">
        <div class="v_header">
        <div class="v_close"></div>
        <span class="vh_name">${title}</span>
        </div>
        <div class="v_body">
        ${code}
        </div>
        </div>
        </div>
        `);

	var popup = $(`#${popupId}`);
	openPopup(popup);

	function openPopup() {
		popup.css('display', 'flex');
		setTimeout(function() {
			popup.css('opacity', 1);
			popup.find('.v_container').css('opacity', 1);
			popup.find('.v_container').css('transform', 'scale(1)');
		}, 10);
	}
	if (typeof callback === 'function') {
		callback();
	}

	function closePopup() {
		popup.find('.v_container').css('transform', 'scale(0.8)');
		popup.css('opacity', 0);
		popup.find('.v_container').css('opacity', 0);
		setTimeout(function() {
			popup.css('display', 'none');
			popup.remove();
			$("body").removeClass("hide-scroll");
		}, 300);
	}

	popup.find('.v_close').on("click", closePopup);
}

function close_windows(el) {
	$(el).closest("._view").fadeOut();
}

function handleResponse(response, el, isToggle) {
	var numberOfNavSubFolders = Number($(".public_folders .nav_sub_folders").length) + 20;
	var $navPathFolders = $(el).closest(".nav_path_folders");
	var $nextElement = $navPathFolders.next('.nav_sub_folders');

	if (isToggle) {
		$(el).html("-");
		if ($nextElement.length === 0) {
			$navPathFolders.after("<div class='nav_sub_folders' style='padding-left:" + numberOfNavSubFolders + "px'>" + response + "</div>");
			$navPathFolders.next(".nav_sub_folders").slideToggle();
		} else {
			$nextElement.html(response);
		}
	} else {
		$(el).html("+");
		$navPathFolders.next("div").slideToggle("slow", function() {
			$(this).remove();
		});
	}
}

function show_nav_folders(id, el) {
	var $navPathFolders = $(el).closest(".nav_path_folders");
	var numberOfNavSubFolders = Number($(".public_folders .nav_sub_folders").length) + 20;
	if ($navPathFolders.find(".more_folders").html() == "+") {
		$(el).closest(".nav_path_folders").addClass("navload");
	}
	if ($(el).html() === "+") {
		$.ajax({
			url: "/call.php",
			method: "GET",
			data: {
				id: id,
				act: "nav_folders"
			},
			success: function(response) {
				handleResponse(response, el, true);
				$(el).closest(".nav_path_folders").removeClass("navload");

			}
		});
	} else if ($(el).html() === "-") {
		handleResponse(null, el, false);
	}
}

function nav_call_folders(id, el) {
	var $navPathFolders = $(el).closest(".nav_path_folders");
	var numberOfNavSubFolders = Number($(".public_folders .nav_sub_folders").length) + 20;
	if ($navPathFolders.find(".more_folders").html() == "+") {
		$(el).closest(".nav_path_folders").addClass("navload");
	}
	$.ajax({
		url: "/call.php",
		method: "GET",
		data: {
			id: id,
			act: "nav_folders"
		},
		success: function(response) {
			handleResponse(response, $(el).prev("span"), true);
			$(el).closest(".nav_path_folders").removeClass("navload");
		}
	});
}

function callFolders(id, type = "folders") {
	$("#list_" + type).slideToggle();
	$.ajax({
		url: "/call.php",
		method: "GET",
		data: {
			id: id,
			act: "call_folders",
			type: type
		},
		success: function(response) {
			$("#list_" + type).html(response);
			$("#list_" + type).slideToggle();
			_load_thumb();
		}
	});

}

function _openFolders(id) {
	_alert("Đang mở...", 500000, "load");
	$.ajax({
		url: "/call.php",
		method: "GET",
		data: {
			id: id,
			act: "get_folders",
			type: "_name"
		},
		success: function(response) {
			//console.log(response);
			res = $.parseJSON(response);
			change_get("folders", id);
			$.ajax({
				url: "/call.php",
				method: "GET",
				data: {
					id: id,
					act: "folders_path",
				},
				success: function(response) {
					$(".head").html(response);
				}
			});
			if (id > 0) {
				document.title = res["_name"];
			} else {
				document.title = "No Dead Link";
			}
			$("#alert").fadeOut();
			callFolders(id);
			callFolders(id, "files");
		}
	});
}