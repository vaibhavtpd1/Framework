<?php

class BaseModel {
    protected $databaseConnection, $table, $modelName, $columnName = [];
    public $id;

    public function __construct() {
      $this->databaseConnection = DatabaseFactory::getDatabaseInstance();
      $this->getTableName();

      // gets model name from table name eg:- table_name will become TableName
      $this->modelName = str_replace(' ', '', ucwords(str_replace('_', ' ', $this->table)));
    }

    public function index() {
        //return $this->databaseConnection->query("select * from $this->table");
        $sql = "select * from ". $this->table;
        return $this->query($sql)->results();
    }

    protected function setTableColumns() {
        $columns = $this->getColumns();
        foreach($columns as $column) {
            $columnName = $column->Field;
            $this->columnName[] = $column->Field;
        }
    }

    public function getColumns() {
        return $this->databaseConnection->getColumns($this->table);
    }

    public function insert($fields) {
        if(!empty($fields)) {
            return $this->databaseConnection->insert($this->table, $fields);
        }
        else {
            ErrorLog::Exception('No fields are defined to insert');
            return false;
        }
    }

    public function update($id, $fields) {
        if(empty($fields) || $id == '') {
            return false;
        }
        else {
            ErrorLog::Exception('No fields are provided to update');
            return $this->databaseConnection->update($this->table, $id, $fields);
        }
    }

    public function delete($id = '') {
        
        if($id == '' && $this->id == '') {
            ErrorLog::Exception('No id provided to delete');
            return false;
        }
        $id = ($id == '') ? $this->id : $id; // if no id is passed, already available id will be used
        return $this->databaseConnection->delete($this->table, $id);
    }

    public function query($sql, $bind = []) {
        return $this->databaseConnection->query($sql, $bind);
    }

    public function getTableName() {

        if (!isset($this->table)) {
          $tableArray = preg_split('/(?=[A-Z])/', str_replace("Model","",get_class($this)));
          $tableString = '';
          foreach($tableArray as $table) {
              $tableString .= '_'.lcFirst($table);
          }
          $tableString .= 's';
          $this->table = ltrim($tableString, '_');
        }
    }

}
