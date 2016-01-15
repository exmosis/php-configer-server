<?php

/*
 * Main init script for confinger site as a whole.
 */

require_once('library/local/classes/MySqlConnection.php');



function autoLoadClassFile($class_name) {
    if (file_exists('.includes/library/local/classes/' . $class_name . '.php')) {
        require_once('library/local/classes/' . $class_name . '.php');
    }
}
spl_autoload_register('autoLoadClassFile');

