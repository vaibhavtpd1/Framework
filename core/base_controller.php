<?php

class BaseController {

  public $modelName = '', $view, $modelPath;

  public function __construct() {
    $this->getModelName();
    $this->getModelPath();
    if(file_exists($this->modelPath)) {
      $this->modelName = new $this->modelName();
    }
    $this->view = new BaseView();
  }

  public function getModelPath() {
    $name = lcfirst(str_replace( 'Controller', '_model.php',  get_class($this)));
    $this->modelPath = ROOTPATH . '/app/models/'.$name;
  }

  public function index() {
    print_r($this->modelName->index());
  }
  
  private function getModelName() {
    $this->modelName = str_replace('Controller', 'Model' , get_class($this));
  }
}

?>
