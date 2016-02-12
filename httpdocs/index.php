<?php

require_once('scripts/init.php');

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
