<?php

$http_request = new HttpRequest(array('host', 'token'));
$host = $http_request->getParameter('host');
$token = $http_request->getParameter('token');

$host_config_manager = new HostConfigManager($host, $token, $GLOBALS['php_configer_server_db_connection']);
$host_config = $host_config_manager->getHostConfig();

echo "<pre>";
print_r($host_config);
echo "</pre>";


