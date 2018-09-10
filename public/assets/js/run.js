(function (){
    d.addEventListener('DOMContentLoaded',function(){
        d.querySelector('button').addEventListener('click',Action);
    });
    d.forms[0].querySelector('input[type="file"]').addEventListener('change',function(){
        var form = d.forms[0];
        var file = form.file.files[0];
        form.querySelector('.custom-file-label').textContent=file.name;
        console.log(file.name);
    });
})();

