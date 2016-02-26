<?php

$http_request = new HttpRequest(array('host', 'token'));
$host = $http_request->getParameter('host');
$token = $http_request->getParameter('token');

$host_config_manager = new HostConfigManager($host, $token, $GLOBALS['php_configer_server_db_connection']);

$response = new JsonResponse();

try {
    $host_config = $host_config_manager->getHostConfig();
    $response->setSuccess(true);
} catch (Exception $e) {
    $response->addError($e->getMessage());
}

$response->go();


