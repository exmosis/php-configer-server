<?php

require_once('library/external/medoo/medoo.php');

class MySqlConnection {

    public $link = null;

    public function __construct($db_host,
                                   $db_user,
                                   $db_pass,
                                   $db_name,
                                   $db_port = 3306) {
    
        $this->link = new medoo(array(
            // required
            'database_type' => 'mysql',
            'database_name' => $db_name,
            'server' => $db_host,
            'username' => $db_user,
            'password' => $db_pass,
            'charset' => 'utf8',
         
            // [optional]
            'port' => $db_port,

        ));
    
     }


}
