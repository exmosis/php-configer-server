<?php

/**
 * Database setup script
 */
 
// fetch and return user's local database config
$db_config = require_once('config/site_config/cfg__db.user.php');

return $db_config;

