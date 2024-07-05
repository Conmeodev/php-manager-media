var xhr;
var canceled = false;

$(document).ready(function() {
  $(window).on('beforeunload', function() {
    if (!canceled) {
      return 'Bạn có chắc muốn hủy tiến trình tải lên không?';
    }
  });

  var uploadArea = $('#uploadfile');
  var progressBar = $('#progress-bar');

  uploadArea.on('dragover', function(event) {
    event.preventDefault();
    uploadArea.addClass('dragover');
  });

  uploadArea.on('dragleave', function(event) {
    event.preventDefault();
    uploadArea.removeClass('dragover');
  });

  uploadArea.on('drop', function(event) {
    event.preventDefault();
    uploadArea.removeClass('dragover');
    var files = event.originalEvent.dataTransfer.files;
    handleFiles(files);
  });

  $('#fileInput').on('change', function() {
    var files = $(this)[0].files;
    handleFiles(files);
  });

  function handleFiles(files) {
    for (var i = 0; i < files.length; i++) {
      uploadFile(files[i]);
    }
  }

  function uploadFile(file) {
    var maxChunkSize = 200 * 1024;
    var fileSize = file.size;
    var chunks = Math.ceil(fileSize / maxChunkSize);
    var start = 0;
    var end = Math.min(maxChunkSize, fileSize);
    var fileIndex = 0;

    function uploadChunk() {
      canceled = false;
      var chunk = file.slice(start, end);
      var formData = new FormData();
      formData.append('file', chunk, file.name + '_' + fileIndex);

      xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php', true);

      xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
          var percent = ((fileIndex * maxChunkSize + e.loaded) / fileSize) * 100;
          progressBar.width(percent + '%');
        }
      };

      xhr.onload = function() {
        if (xhr.status === 200) {
          fileIndex++;
          if (end < fileSize) {
            start = end;
            end = Math.min(start + maxChunkSize, fileSize);
            uploadChunk();
          } else {
            console.log('Tất cả các chunk đã được tải lên thành công!');
          }
        } else {
          console.error('Lỗi trong quá trình tải lên chunk:', xhr.statusText);
        }
      };

      xhr.onerror = function() {
        console.error('Có lỗi xảy ra trong quá trình tải lên chunk.');
      };

      xhr.send(formData);
    }

    uploadChunk();
  }
});

$(window).on('unload', function() {
  canceled = true;
  if (xhr) {
    xhr.abort();
  }
});