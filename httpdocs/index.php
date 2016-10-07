<?php

/**
 * Entry point for calling valid actions that are configured in the web_entries folder. 
 */

require_once('scripts/init.php');

if (! isset($_GET['__configer_web_entry'])) {
    echo 'No action found.';
    exit;
}

$script = $_GET['__configer_web_entry'];
unset($_GET['__configer_web_entry']);

try {
    if (file_exists($GLOBALS['php_configer_server_web_entries_dir'] . $script . '.php')) {
        require_once($GLOBALS['php_configer_server_web_entries_dir'] . $script . '.php');
    } else {
        header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
		die;
    }
} catch (Exception $e) {
    // Should setup an HtmlResponse here instead
    header($_SERVER["SERVER_PROTOCOL"]." 500 Server Error", true, 500);
    echo '<h1>Error</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
	die;
}

exit;

