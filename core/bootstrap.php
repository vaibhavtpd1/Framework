<?php

// Gets database files 
require_once(ROOTPATH . '/config/config.php');
require_once(ROOTPATH . '/config/database_config.php');
require_once(ROOTPATH . '/core/error_log.php');

// Autoload  classes
function __autoload($className) {

    $className = convertToFileName($className);
    
    if(file_exists(ROOTPATH . '/database/' . $className . '.php')) {  
        require_once(ROOTPATH . '/database/database_interface.php');      
        require_once(ROOTPATH . '/database/database_factory.php');
        require_once(ROOTPATH . '/database/' . $className . '.php');
    }
    else if(file_exists(ROOTPATH . '/core/' . $className . '.php')) {
        require_once(ROOTPATH . '/core/' . $className . '.php');
    }
    else if(file_exists(ROOTPATH . '/app/models/' . $className . '.php')) {
        require_once(ROOTPATH . '/app/models/' . $className . '.php');
    }
    else if(file_exists(ROOTPATH . '/session/' . $className . '.php')) {
        require_once(ROOTPATH . '/session/' . $className . '.php');
    }
    else if(file_exists(ROOTPATH . '/config/' . $className . '.php')) {
        require_once(ROOTPATH . '/config/' . $className . '.php');
    }
    else {
        die('Cannot include files' . $className);
    }
}

// Convert pascal case to framework file name case
function convertToFileName($className) {
    $convertedClassCase = '';
    $subStringArray = $pieces = preg_split('/(?=[A-Z])/',$className);
    foreach($subStringArray as $subStringArrayElement) {
        $convertedClassCase .= '_' . lcfirst($subStringArrayElement);
    }
    return ltrim($convertedClassCase,'_');
}