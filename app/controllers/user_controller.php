<?php

class UserController extends BaseController {

	function __construct()
	{
		parent::__construct();
	}

    
  	public function insertUser() {
      	$this->view->postedData($postArray);
      	$this->view->render('user');
  	}

  	public function getUser() {
      $this->view->postedData($this->databaseConnection->query('select * from users')->results());
      $this->view->render('home/about');
  	}

  	function signup() {
		$this->view->render('signup');
  	}

	public function login()
	{
		$this->view->render('login');
	}
	
	public function showUsers() {
		$middleware=new Middleware;
		if($middleware->secureHandle()){
			$this->modelName->getUsers();
		}else{
			echo "Unauthorised";
		}
	}
	public function deleteAccount() {
		$session = new Session;
		$userSession = explode(",",$session->getSession());
		$userId = $userSession[1];
		$sessionObj = new SessionModel();
		$sessionObj->deleteUserSession($userId);
		$this->modelName->deleteUser($userId);
		$session->deleteSession();
		$this->view->render("signup");
	}
	
	function register(){
		$arr = ["name" => $_POST['name'],
				"email" =>$_POST['email'],
				"password" => $_POST['password']
				];
		
		$this->view->postedData($arr);
		$res=$this->modelName->insert($this->view->getpostedData());
		if($res==1){
			$this->view->render("login");
		}
		else{
			echo "Record Cannot be Inserted";
		}
	} 
}
