$(document).ready(function() {
    $(document).on('contextmenu', '.box', function(event) {
        _alert(`Đang lấy dữ liệu.`,30000000,`wait`);
        event.preventDefault();
        const box = $(this);
        const elementId = box.attr('_idfolders');
        if (elementId) {
            $.ajax({
                url: "/call.php",
                method: "GET",
                data: {
                    act: "get_folders",
                    id: elementId
                },
                success: function(response) {
                    $("#alert").fadeOut();
                    res = $.parseJSON(response);
                    const elementName = box.find('.name').text();
                    const dialogContent = `
                    <div class="form" id="edit" val="${elementId}">
                    <div class="group-input">
                    <label for="name_folders">Tên</label>
                    <div class="group-in">
                    <input type="text" id="name_folders"  value="${elementName}" placeholder="" disabled>
                    <span class="btn center" id="btnRename" onclick="inputRenameFoloders();">✎ </span>
                    </div>
                    </div>
                    <div class="group-input">
                    <label for="elementDateInput">Ngày tháng </label>
                    <input type="text" id="elementDateInput" value="` + res["_time"] + `" disabled>
                    </div>
                    <div class="group-input">
                    <label for="cmd_folders">Command </label>
                    <div class="group-in">
                    <input type="text" id="cmd_folders"  value="" placeholder="Password & Cmd">
                    <span class="btn center" id="btnCmd" onclick="inputCmdFolders();">⚚</span>
                    </div>
                    </div>
                    
                    <!--button onclick="panel("xoa",${elementId})" class="b-red">Xóa</button-->
                    
                    </div>
                    `;
                    $("#edit").closest("._view").remove();
                    _windows(`${elementName}`, dialogContent);
                }
            });
        } else {
            const elementId = box.attr('_idfiles');
            if (elementId) {
                $.ajax({
                    url: "/call.php",
                    method: "GET",
                    data: {
                        act: "get_file",
                        id: elementId
                    },
                    success: function(response) {
                        $("#alert").fadeOut();
                        res = $.parseJSON(response);
                        const elementName = box.find('.name').text();
                        const dialogContent = `
                        <div class="form" id="edit" val="${elementId}">
                        <div class="group-input">
                        <label for="name_folders">Tên</label>
                        <div class="group-in">
                        <input type="text" id="name_files"  value="${elementName}" placeholder="" disabled>
                        <span class="btn center" id="btnRename" onclick="inputRenameFiles();">✎ </span>
                        </div>
                        </div>
                        <div class="group-input">
                        <label for="elementDateInput">Ngày tháng </label>
                        <input type="text" id="elementDateInput" value="` + res["_time"] + `" disabled>
                        </div>
                        <div class="group-input">
                        <label for="elementDateInput">Kích thước </label>
                        <input type="text" id="elementDateInput" value="` + _size(res["_size"]) + `" disabled>
                        </div>
                        <div class="group-input">
                        <label for="cmd_folders">Command </label>
                        <div class="group-in">
                        <input type="text" id="cmd_file"  value="" placeholder="Password & Cmd">
                        <span class="btn center" id="btnCmd" onclick="inputCmdFile();">⚚</span>
                        </div>
                        </div>

                        <!--button onclick="panel("xoa",${elementId})" class="b-red">Xóa</button-->

                        </div>
                        `;
                        $("#edit").closest("._view").remove();
                        _windows(`${elementName}`, dialogContent);
                    }
                });
                
            } else {
                console.log("no box");
            }
        }
    });
});
function inputCmdFolders(){
    var _cmd = $("#cmd_folders").val();
    var id = $("#edit").attr("val");
    if(_cmd == "delete"){
        updateFolders("delete",id);
    }
}
function inputCmdFile(){
    var _cmd = $("#cmd_file").val();
    var id = $("#edit").attr("val");
    if(_cmd == "delete"){
        updateFiles("delete",id);
    }
}
function inputRenameFoloders(){
    var id = $("#edit").attr("val");
    var btn = $("#btnRename");
    var input = $("#name_folders");
    
    if(input.prop('disabled')) {
        input.attr("disabled",false);
        input.focus();
        btn.html("√");
        btn.css("background","#47c336");
        btn.attr("onclick","updateFolders('rename',"+id+")");
    }
}
function inputRenameFiles(){
    var id = $("#edit").attr("val");
    var btn = $("#btnRename");
    var input = $("#name_files");
    
    if(input.prop('disabled')) {
        input.attr("disabled",false);
        input.focus();
        btn.html("√");
        btn.css("background","#47c336");
        btn.attr("onclick","updateFiles('rename',"+id+")");
    }
}
function updateFolders(act,id){
    if(act == "rename") {
        var name = $("#name_folders");
        var btn = $("#btnRename");
        $.ajax({
            url: "/response.php",
            method: "POST",
            data: {id:id,act:"rename_folders",name:name.val()},
            success: function(response){
                res = $.parseJSON(response);
                if(res.res == "true") {
                    $("#folders"+id).find(".name").html(name.val());
                    $(".vh_name").html(name.val());
                    _alert(res.content,3000,"success");
                    name.attr('disabled',true);
                    btn.html("✍");
                    btn.css("background","");
                    btn.attr("onclick","inputRename()");
                } else {
                    _alert(res.content,3000,"error");
                }
            }
            
        });
    } else if(act == "delete") {
        _alert("Đang xóa thư mục và tập tin bên trong...",30000000);
        $.ajax({
            url: "/response.php",
            method: "POST",
            data: {id:id,act:"delete_folders"},
            success: function(response){
                res = $.parseJSON(response);
                if(res.res == "true") {
                    $("._view").fadeOut();
                    $("#folders"+id).fadeOut();
                    _alert(res.content,3000,"success");
                } else {
                    _alert(res.content,3000,"error");
                }
            }
            
        });
    }
}
function updateFiles(act,id){
    if(act == "rename") {
        var name = $("#name_files");
        var btn = $("#btnRename");
        $.ajax({
            url: "/response.php",
            method: "POST",
            data: {id:id,act:"rename_files",name:name.val()},
            success: function(response){
                res = $.parseJSON(response);
                if(res.res == "true") {
                    $("#file"+id).find(".name").html(name.val());
                    $(".vh_name").html(name.val());
                    _alert(res.content,3000,"success");
                    name.attr('disabled',true);
                    btn.html("✍");
                    btn.css("background","");
                    btn.attr("onclick","inputRenameFiles()");
                } else {
                    _alert(res.content,3000,"error");
                }
            }
            
        });
    } else if(act == "delete") {
        _alert("Đang xóa tập tin...",30000000);
        $.ajax({
            url: "/response.php",
            method: "POST",
            data: {id:id,act:"delete_files"},
            success: function(response){
                res = $.parseJSON(response);
                if(res.res == "true") {
                    $("._view").fadeOut();
                    $("#file"+id).fadeOut();
                    _alert(res.content,3000,"success");
                } else {
                    _alert(res.content,3000,"error");
                }
            }
            
        });
    }
}

















