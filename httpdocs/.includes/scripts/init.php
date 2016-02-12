<?php

/*
 * Main init script for configer site as a whole.
 */

/** Class autoloading setup **/

function autoLoadClassFile($class_name) {
    if (file_exists('.includes/library/local/classes/' . $class_name . '.php')) {
        require_once('library/local/classes/' . $class_name . '.php');
    }
}
spl_autoload_register('autoLoadClassFile');

 
/** Database connection setup **/
 
// require_once('library/local/classes/MySqlConnection.php');
$GLOBALS['php_configer_server_db_config'] = 
                                require_once('config/cfg__db.php');
$GLOBALS['php_configer_server_db_connection'] = 
                                new MySqlConnection($GLOBALS['php_configer_server_db_config']);

