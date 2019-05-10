<?php

class Mysql implements DatabaseInterface {

    public static $instance = null;

    private $pdo, $query, $error = false, $result, $count = 0, $lastInsertId = null;

    // connection fields
    private $databaseServer, $host, $databaseName, $user, $password;
    
    /*
        Database connection having a Singleton Design Pattern.
    */
    private function __construct($dbConfigArray) {
        
        // connection fields
        $this->databaseServer = $dbConfigArray['dbserver'];
        $this->host = $dbConfigArray['hostname'];
        $this->databaseName = $dbConfigArray['database'];
        $this->user = $dbConfigArray['username'];
        $this->password = $dbConfigArray['password'];

        $this->pdo = new PDO($this->databaseServer.':host='.$this->host.';dbname='.$this->databaseName,$this->user,$this->password);
        
    }
    
    public static function getInstance($dbConfigArray) {
        if(!isset(self::$instance)) {           
            self::$instance = new Mysql($dbConfigArray);
        }

        return self::$instance;
    }
    
    // Method to get records and also to query insert, update and delete
    public function query($sql, $params = []) {
        $this->error = false;
        if($this->query = $this->pdo->prepare($sql)) {
            $paramCount = 1;
            if(count($params)) {
                foreach($params as $param) {
                    $this->query->bindValue($paramCount, $param);
                    $paramCount++;
                }
            }

            if($this->query->execute()) {
                $this->result = $this->query->fetchAll(PDO::FETCH_OBJ);
                $this->count = $this->query->rowCount();
                $this->lastInsertId = $this->pdo->lastInsertId();
            }
            else {
                ErrorLog::Exception("Cannot execute query");
                $this->error = true;
            }
        }
        return $this;
    }

    public function insert($table, $fields = []) {
        $fieldString = '';
        $valueString = '';
        $values = [];

        foreach($fields as $field =>$value) {
            $fieldString .= '`' . $field . '`,'; 
            $valueString .= '?,';
            $values[] = $value;
        }

        // Remove extra ',' seperators
        $fieldString = rtrim($fieldString, ',');
        $valueString = rtrim($valueString, ',');

        $sql = "INSERT INTO {$table} ({$fieldString}) VALUES({$valueString})";
        if($this->query($sql, $values)->error()) {
            ErrorLog::Exception("Cannot insert record");
            return false;
        }
        return true;
    }

    public function update($table, $id, $fields = []) {
        $fieldString = '';
        $values = [];

        foreach($fields as $field => $value) {
            $fieldString .= ' ' . $field . ' = ?,';
            $values[] = $value;
        }
        // Remove any extra white spaces 
        $fieldString = trim($fieldString);
        // Remove ending ','
        $fieldString = rtrim($fieldString, ',');

        $sql = "UPDATE {$table} SET {$fieldString} WHERE id = {$id}";
        if($this->query($sql, $values)->error()) {
            ErrorLog::Exception("Cannot update record");
            return true;
        }
        return false;
    }

    public function delete($table, $id) {
        $sql = "DELETE FROM {$table} WHERE id = {$id}";
        if($this->query($sql)->error()) {
            ErrorLog::Exception("Cannot delete record");
            return true;
        }
        return false;
    }

    public function results() {
        if(empty($this->result)) {
            $errorMsg = "No result found";
            ErrorLog::Exception($errorMsg);
            throw new Exception($errorMsg);
        }
        else {
            return $this->result;
        }
    }

    public function first() {
        return (!empty($this->result)) ? $this->result[0] : [];
    }

    public function count() {
        return $this->count;
    }

    public function lastInsertId() {
        return $this->lastInsertId;
    }

    public function getColumns($table) {
        $getCols = $this->query("SHOW COLUMNS FROM {$table}")->results();
        if(empty($getCols)) {
            $errorMsg = "No columns found";
            ErrorLog::Exception($errorMsg);
            throw new Exception($errorMsg);
        }
        else {
            return $getCols;
        }
    }

    public function error() {
        return $this->error;
    }
}
