var d = document;
var validate={
    file:function(file) {
        var fExt = file.substring(file.lastIndexOf(".")+1)
        if(fExt === "zip"){
            return true;
	}
        return false;
    }
}

function UploadFileAjax(url,objdata,callback){
    var xhr = new XMLHttpRequest();
    xhr.open('POST', url, true);
    xhr.onload = callback;
    xhr.send(objdata);
}

function setOverlay() {
    var overlay=new Element('div',{'id':'_overlay','style':'display: block;position: fixed;top: 0px;bottom: 0px;left: 0px;right: 0px;background: rgba(0,0,0,0.3);z-index: 4;'});
    overlay.innerHTML='<div style="margin-left:50%;margin-top:30%;"><ima src=/images/loading.gif></div>';
    return overlay;
}

function delOverlay() {
    d.getElementById('_overlay').remove();
}

function Action(e) {
    var form=d.forms[0];
    var input = form.file;
    var file = input.files[0];
    if (validate.file(file.name)===false) {
        alert('Неверное разрешение файла');
        return false;
    }
    var data = new FormData();
    data.append("file", file);
    UploadFileAjax("/FileUpload",data,function(e){
        e.preventDefault();
        if (e.target.status === 200) {
            var result=JSON.parse(e.target.responseText);
            result=JSON.parse(result);
            if (result.success) {
                var download=d.getElementById('download');
                var a=download.querySelector('a');
                a.href=result.href;
                download.style.display='block';
            }
            else {
                console.log(arrMsg.errorScript);
            }
        } else if (e.target.status !== 200) {
            console.log(arrMsg.errorRequest + e.target.status);
        }
    })
}