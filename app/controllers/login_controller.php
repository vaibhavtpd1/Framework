<?php

class LoginController extends BaseController {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "Index Method";
	}

	public function login()
	{
		$session=new Session;
		$userModel= new UserModel;
		$userSession=explode(",",$session->getSession());
		if(!(empty($userSession) || $userSession[0]=="")){
			$users=$userModel->getUserByEmail($_SESSION['email']);
			$this->view->postedData($users);
			$this->view->render('dashboard');
			return;
		}
		if(!session_id())
		{
			session_start();
		}
		
		$_SESSION['email']=$_POST['email'];
		$users=$userModel->getUserByEmail($_POST['email']);
		if(count($users)<1){
			throw new Exception("Email id not found !");
		}
		$userPassword=$userModel->getPasswordByEmail($users[0]->email);
		if(count($userPassword)<1){
			throw new Exception("Password not found !");
		}
		if($_POST['password']==$userPassword[0]->password) {
			$session=new Session;
			$uuid=$session->createSession($users[0]->id);
			$sessionModel=new SessionModel;
			$isInserted=$sessionModel->insertSession($uuid,date('Y-m-d H:i:s',time() + (60 * 30)),$users[0]->id);
			if(!$isInserted){
				throw new Exception("Unable to insert session data !");	
			}
			$this->view->postedData($users);
			$this->view->render('dashboard');

		}else{
			die("Email id or password is incorrect !");
		}
	}
	public function renderLogin(){
		//header('Location:/Framework/app/views/login.html');
		$this->view->render("login");
	}
	public function showUsers() {
		$userId = $_Session['id'];
		echo $userId;
	}
	public function logout(){
		$session=new Session;
		$userSession=explode(",",$session->getSession());
		if(empty($userSession) || $userSession[0]==""){
			throw new Exception("Session data not found !");
		}
		$sessionModel =new SessionModel;
		$userDbSession=$sessionModel->sessionByUserIdAndUuId($userSession[1],$userSession[0]);
		if(count($userDbSession)<1){
			throw new Exception("DB session data not found !");
		}

		$sessionModel->deleteUserSession($userSession[1]);
		$session->deleteSession();
		//header('Location:/Framework/app/views/login.html');
		$this->view->render("login");
	}
}
?>
