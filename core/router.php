<?php

class Router {
  const DEFAULT_CONTROLLER = "IndexController";
  const DEFAULT_ACTION     = "index";

  private $controller = self::DEFAULT_CONTROLLER;
  private $action = self::DEFAULT_ACTION;
  private $params = array();
  private $pathArray = array();
  private $errInstance;

  private function parseUri() {

    if(isset($_SERVER['PATH_INFO'])) {
      $path = $_SERVER['PATH_INFO'];
      $pathSplit = explode('/', ltrim($path));
    }
    else {
      $pathSplit = '/';
    }
    $this->pathArray = $pathSplit;
  }

  private function setAction() {
    $this->action = isset($this->pathArray[2])? $this->pathArray[2] : '';
  }

  private function setParams() {
    if(sizeof($this->pathArray) > 2)
      $this->params = array_slice($this->pathArray, 3);
  }

  private function setController() {

    if( $this->pathArray === '/') {
      echo "Redirect to Home Page \n";
    }
    else {
      $reqController = $this->pathArray[1];
      $reqModel = $this->pathArray[1];
      $controllerPath = ROOTPATH . '/app/controllers/'.$reqController.'_controller.php';
      $modelPath = ROOTPATH . '/app/models/'.$reqModel.'_model.php';

      if (file_exists($controllerPath))
      {
        include_once $controllerPath;
        $model = ucfirst($reqModel).'Model';
        $this->controller = ucfirst($reqController).'Controller';

        $method = $this->action;
        
        if (file_exists($modelPath)) {
          include_once $modelPath;
        }
        $controllerObj = new $this->controller();

        if ($method != '') {
          if (method_exists($controllerObj, $method)) {
            if( sizeof($this->params) )
              $controllerObj->$method($this->params);
            else
              $controllerObj->$method();
          }
          else {
            $error = 'No such Method Found or 404 error';
            ErrorLog::Exception($error);
            die($error);
          }
        }
        else {
          echo "No method metioned. We will set a default path";
        }
      }
      else {
        $error = 'No Such Controller found. 404 - The file not found';
        ErrorLog::Exception($error);
        header('HTTP/1.1 404 Not Found');
        die($error);
      }
    }
  }

  public function run() {
    $this->parseUri();
    $this->setAction();
    $this->setParams();
    $this->setController();
  }
}
?>
