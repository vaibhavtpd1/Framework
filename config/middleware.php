<?php
class Middleware{
    
    
    public function secureHandle()
    {
        if(!$this->updateSession()){
			throw new Exception("Unauthenticated User. Logging Out !");
        }
        return true;
    }

    public function updateSession(){
		$DbSessionData=$this->authenticateUser();
		$session=new Session;
		if(count($DbSessionData)<1){
			$session->deleteSession();
			throw new Exception("Unauthenticated User. Logging Out !");
        }
        $DbSessionValues=array_values($DbSessionData);
		$expireTime=strtotime($DbSessionValues[2]);
		if (time()-$expireTime>=1800){
			$sessionModel =new SessionModel;
			$sessionModel->deleteSession(array_search("id",array_keys($DbSessionData)));
			throw new Exception("Unauthenticated User. Logging Out !");
		}
		$session->updateSession();
		return true;
    }
    
	public function authenticateUser(){
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
		if($sessionModel->updateSession($userDbSession[0]->id,$userDbSession[0]->uuid,date('Y-m-d H:i:s',time() + (60 * 30)),$userDbSession[0]->user_id)){
			throw new Exception("Unable to update session data !");	
		}
		$sessionArray = array(
			"id" =>$userDbSession[0]->id,
            "uuid" => $userDbSession[0]->uuid,
            "expires_at" => date('Y-m-d H:i:s',time() + (60 * 30)),
            "user_id" => $userDbSession[0]->user_id
		);
		return $sessionArray;
	}
}
?>