<?php
/*
 * Класс загрузки файла на сервер
 *
 */
class FileUpload extends Page {

    function initialize()
    {
        $this->SetViewDisable();
        try
        {
            $data['success']=false;
            if (is_uploaded_file($_FILES['file']['tmp_name'])) {
                $fname=$_FILES['file']['name'];
                $res = move_uploaded_file($_FILES['file']['tmp_name'], APP_PATH_UPLOAD.$fname);
                if ($res) {
                    $fUnzip=(new SplFileInfo(APP_PATH_UPLOAD.$fname))->getBasename(substr($fname, -4));
                    if(!file_exists(APP_PATH_UPLOAD.$fUnzip)) mkdir(APP_PATH_UPLOAD.$fUnzip, 0777);
                    $zip = new ZipArchive;
                    $res = $zip->open(APP_PATH_UPLOAD.$fname);
                    if ($res === true) {
                        $zip->extractTo(APP_PATH_UPLOAD.$fUnzip);
                        $zip->close();
                    }
                    $this->renameFiles(APP_PATH_UPLOAD.$fUnzip);
                    $this->createZip($fname,APP_PATH_UPLOAD.$fUnzip);
                    rename(APP_PATH_UPLOAD.$fname, APP_PATH.'public/download/'.$fname);
                    $data['success']=true;
                    $data['href']='/download/'.$fname;
                }
            }
            return json_encode($data);
        }
        catch ( Exception $e )
        {
            die ( $e->getMessage().'**FileUpload');
        }
    }

    /*
     * Транслит имен файлов
     */

    function renameFiles($pathDir)
    {
        $it = new DirectoryIterator($pathDir);
        foreach ($it as $finfo) {
            if (!$finfo->isDot()) {
                $fname = $finfo->getFilename();
                if ($finfo->isDir()) {
                    $this->renameFiles($pathDir.'/'.$fname);
                }
                $fnewname= $this->translit($fname);
                rename($pathDir.'/'.$fname, $pathDir.'/'.$fnewname);
            }
        }
        return true;
    }

    function  translit($fname)
    {
        if(!preg_match ("/[А-Яа-я]/u", $fname)) {
            $fname=iconv('cp866','UTF-8//IGNORE',
                    iconv('cp437', 'cp865//IGNORE',
                     iconv('UTF-8', 'cp437//IGNORE',$fname)));
        }
        $translit=['а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l',
                    'м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c',
                    'ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''];
        $cyr= array_keys($translit);
        $lat= array_values($translit);
        return str_replace($cyr,$lat,mb_strtolower($fname));
    }

    function createZip($zipfile,$path)
    {
        $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path,RecursiveDirectoryIterator::SKIP_DOTS),RecursiveIteratorIterator::SELF_FIRST);
        $zip = new ZipArchive;
        $zip->open(APP_PATH_UPLOAD.$zipfile,ZipArchive::CREATE | ZipArchive::OVERWRITE);
        $lPath=strlen($path);
        $key=0;
        $subDir='';
        foreach ($it as $finfo) {
            if ($finfo->isDir()) {
                $Dir=$finfo->getFilename();
                $subDir.=$Dir.'/';
                $zip->addEmptyDir($subDir);
            } else {
                $fname = $finfo->getFilename();
                if (!empty($subDir)){
                    $zip->addFromString($subDir.$fname,
                        file_get_contents($finfo->getPath().'/'.$fname));
                } else {
                    $zip->addFile($finfo->getPath().'/'.$fname,$fname);
                }
            }
            $key++;
        }
        $zip->close();
        $delDir=[];
        foreach ($it as $finfo) {
            if ($finfo->isFile()) {
                unlink($finfo->getPath().'/'.$finfo->getFilename());
            }
            if ($finfo->isDir()) {
                $delDir[]=$finfo->getPath().'/'.$finfo->getFilename();
            }
        }
        arsort($delDir);
        foreach ($delDir as $dir) {
              rmdir($dir);
        }
    }
}


