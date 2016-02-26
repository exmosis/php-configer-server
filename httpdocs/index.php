<?php

require_once('scripts/init.php');

$script = $_GET['__configer_web_entry'];
unset($_GET['__configer_web_entry']);

try {
    if (file_exists($GLOBALS['php_configer_server_web_entries_dir'])) {
        require_once($GLOBALS['php_configer_server_web_entries_dir'] . $script . '.php');
    } else {
        echo 'ERROR 404'; // placeholder
    }
} catch (Exception $e) {
    // Should setup an HtmlResponse here instead
    echo '<h1>Error</h1>';
    echo '<p>' . $e->getMessage() . '</p>';
}

exit;

// echo 'Configer OK';

echo "<p>Configer site OK. Getting setup for domain 'test'...</p>";

$hc_manager = new HostConfigManager('test', $GLOBALS['php_configer_server_db_connection']);
$host_config = $hc_manager->getHostConfig();

echo "<pre>";
print_r($host_config);
echo "</pre>";

if ($host_config->getId()) {
    echo "<p>Looks like the database is working...</p>";
} else {
    echo "<p>Didn't get an ID for the test domain. Is the database config working? Is there an entry for the 'test' domain?</p>";
}
