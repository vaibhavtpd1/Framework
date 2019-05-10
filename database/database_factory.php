<?php

class DatabaseFactory {
    public static function getDatabaseInstance() {
        
        global $db;
        $dbConfigName = $db['dbserver'];

        switch($dbConfigName) {
            case 'mysql' :
                return Mysql::getInstance($db);
            case 'pgsql' :
                return Pssql::getInstance($db);
            default :
                die('Provide database server name in database_config.php');
        }
    }
}