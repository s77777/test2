<?php
/*
 @main class
*/

class Page {

    protected   $args = array();
    protected   $view=APP_PATH_VIEWS;
    protected   $params;
    public      $PageData=array();
    protected   $settings=array();
    public      $ViewTemplate=true;
    protected   $Class;
    protected   $action;

    function __construct($params=null) {
          $this->params=$params;
          $this->args=$this->getUri();
    }

    function getContent(){
          $this->Class=$this->args['class'];
          $this->method=$this->args['method'];
          $Class = new $this->Class($this->args['params']);
          if (in_array($this->method,get_class_methods($this->Class)))
          {
                $data = $Class->{$this->method}();
                header('X-Frame-Options: DENY');
                header('Content-Security-Policy "default-src \'self\';"');
                header('X-XSS-Protection "1; mode=block"');
                header('X-Content-Type-Options nosniff');
                if ($Class->GetViewTemplate()){
                    $PageData=$Class->getPageData();
                    $PageData['Class'] =  $this->Class;
                    require_once $this->view.get_class($Class).'/'.
                            (($this->args['method']=='initialize')?'index.tpl':$this->args['method'].'.tpl');
                } else {
                    header('Content-Type:application/json');
                    echo json_encode($data);
                }
          }
          else
          {
            echo "No action name $this->method for this classe";
          }
    }

    function getParams() {
      return $this->params;
    }

    protected function forward($uri)
    {
        $uriParts = explode('/', trim($uri, '/\\'));
        $params = array_slice($uriParts, 2);
        return array(
                    'class' => (empty($uriParts[0]))?'Index':$uriParts[0],
                    'method' => (!empty($uriParts[1]))?$uriParts[1]:'initialize',
                    'params' => (!empty($params))?$params:null,
                );
    }

    protected function getUri() {
        $route = $_GET['_url'];
        return $this->forward(str_replace('.','',$route));
    }

    function setPageData($name,$value) {
        $this->PageData[$name] = $value;
    }

    function getPageData() {
      return $this->PageData;
    }

    function SetViewDisable() {
        $this->ViewTemplate=false;
    }

    function GetViewTemplate() {
        return $this->ViewTemplate;
    }

    function getClassData($classname) {
        return include($this->LangPath.$this->lang.'/'.$classname.'PageData.php');
    }

}
