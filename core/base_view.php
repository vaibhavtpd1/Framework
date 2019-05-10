<?php
class BaseView{

    protected $head, $body, $siteTitle = SITE_TITLE, $outputBuffer, $layout = DEFAULT_LAYOUT;
    private $dataArray;

    public function __construct() {
    }

    public function render($viewName) {
    
        if(file_exists(ROOTPATH . '/app/views/' . $viewName . '.php')){
            include(ROOTPATH . '/app/views/' . $viewName . '.php');
            include(ROOTPATH . '/app/views/layouts/' .$this->layout. '.php');
        }
        else{
            die('The View \"'.$viewName.'\" does not exist.');
        }
    }

    public function content($type) {
        if($type == 'head') {
            return $this->head; 
        }
        elseif ($type == 'body') {
            return $this->body;
        }
        return false;
    }

    public function start($type) { 
        // $type will be head or body depending on user view
        $this->outputBuffer = $type;
        ob_start(); // default php method to store buffer, in this case <html> tags
    }

    public function end() {
        if($this->outputBuffer == 'head'){
            $this->head = ob_get_clean();
        }
        elseif($this->outputBuffer == 'body') {
            $this->body = ob_get_clean();
        }
        else{
            ErrorLog::Exception('Run the start method before render your page');
            die('Run the start method before render your page');
        }
    }

    public function siteTitle() {
        return $this->siteTitle;
    }

    public function setSiteTitle($title) { 
        $this->siteTitle = $title;
    }

    public function setLayout($path){
        $this->layout = $path;
    }

    public function postedData($dataArray = []) {
        $this->dataArray = $dataArray;
    }

    public function getPostedData() {
        return $this->dataArray;
    }
}
