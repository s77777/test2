<!DOCTYPE HTML>
<html lang="ru">
<head>
<title></title>
<?php include_once(APP_PATH_VIEWS .'HeadMeta.tpl');?>
</head>
<body>
<div class="container">
    <h1>Тест</h1>
   <div class="card col-sm-6 col-md-6">
    <div class="card-body">
        <form class="was-validated">
            <div class="custom-file">
                <div class="form-group mx-sm-3 mb-2">
                    <input type="file" name="file" class="custom-file-input" value="" required>
                    <label class="custom-file-label">Файл...</label>
                    <div class="invalid-feedback">Файл не выбран</div>
                </div>
            </div>
        </form>
    </div>
    <div class="form-group mx-3">
        <button class="btn btn-primary" type="buttom" name="Upload">Загрузить файл</button>
    </div>
   </div>
    <div id="download" class="card-body" style="display:none;">
        <div class="form-group mx-3">
            <a href="" class="btn btn-primary" type="buttom">Скачать файл</a>
        </div>
    </div>
</div>
<!-- Scripts -->
<?php include_once(APP_PATH_VIEWS .'Jscript.tpl');?>
</body>
</html>
