<?php

require_once('library/external/medoo/medoo.php');

class MySqlConnection {

    public $link = null;

    public function __construct(DatabaseConfig $db_config) {

        $this->link = new medoo(array(
            // required
            'database_type' => 'mysql',
            'database_name' => $db_config->getDb(),
            'server' => $db_config->getHost(),
            'username' => $db_config->getUser(),
            'password' => $db_config->getPass(),
            'charset' => 'utf8',
         
            // [optional]
            'port' => $db_config->getPort(),

        ));
    
     }


}
