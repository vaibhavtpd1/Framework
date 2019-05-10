<?php

class SessionModel extends BaseModel {

    public function __construct() {
        parent::__construct();
    }   
    
    public function insertSession($uuid,$expiresAt,$userID){
        $sessionArray = array(
            "uuid" => $uuid,
            "expires_at" => $expiresAt,
            "user_id" => $userID
        );
        return $this->databaseConnection->insert('sessions',$sessionArray);
    }

    public function updateSession($id,$uuid,$expiresAt,$userID){
        $sessionArray = array(
            "uuid" => $uuid,
            "expires_at" => $expiresAt,
            "user_id" => $userID
        );
        return $this->databaseConnection->update('sessions',$id,$sessionArray);
    }

    public function deleteSession($id) {
        $this->databaseConnection->delete('sessions',$id);
    }

    public function sessionByUserIdAndUuId($userID,$uuid){
    $sql="SELECT * from sessions where uuid='{$uuid}' and user_id='{$userID}'";
        $result=$this->databaseConnection->query($sql);
        return $result->results();
    }

    public function deleteUserSession($userId) {
        $arr = ["user_id" => $userId];
        $sql="DELETE from sessions where user_id=?";
        $this->databaseConnection->query($sql,$arr);
    }
}

?>