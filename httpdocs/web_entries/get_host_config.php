<?php

$http_request = new HttpRequest(array('host', 'token'));
$host = $http_request->getParameter('host');
$token = $http_request->getParameter('token');

$host_config_manager = new HostConfigManager($host, $token, $GLOBALS['php_configer_server_db_connection'], new ConfigVarManager($GLOBALS['php_configer_server_db_connection']));
$config_output_file_manager = new ConfigOutputFileManager($GLOBALS['php_configer_server_db_connection']);

$response = new JsonResponse();

$host_config = $host_config_manager->getHostConfig();
$config_vars = $host_config->getConfigVarsWithinFileInfo($config_output_file_manager);
echo json_encode($config_vars);
