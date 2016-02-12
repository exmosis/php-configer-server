<?php

class DatabaseConfig {
    
    protected $host = null;
    protected $user = null;
    protected $pass = null;
    protected $db = null;
    protected $port = 3306;
    
    public function __construct($host, $user, $pass, $db) {

        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->db = $db;
        
    }
    
    public function getHost() {
        return $this->host;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPass() {
        return $this->pass;
    }

    public function getDb() {
        return $this->db;
    }

    public function getPort() {
        return $this->port;
    }

}
