<?php

/**
 * Object representing a final config setup for a single host.
 */
class HostConfig {
    
    const DB_TABLE__HOSTS = 'hosts';
    
    protected $domain = null;
    protected $db_connection = null;
    
    protected $id = null;
    protected $token = null;
    protected $parent_host_id = null;
    protected $parent_host = null;

    /**
     * Constructor. Requires a domain and a database connection.
     * 
     * @param $host_domain  String  Domain to use for host - no http:// or trailing /
     * @param $db_connection MySqlConnection MySQL Database Connection object to use  
     */    
    public function __construct($host_domain, MySqlConnection $db_connection) {
        $this->domain = $host_domain;
        $this->db_connection = $db_connection;
        $this->fetchHostDetails();
    }
    
    public function fetchHostDetails() {
        $host_info = $this
                        ->db_connection
                        ->link
                        ->select(
                            self::DB_TABLE__HOSTS,
                            array(
                                'host_id',
                                'host_token',
                                'parent_host_id'
                            ),
                            array(
                                'host_domain' => $this->getDomain()
                            )
                        );
                        
        $host_info = $host_info[0];
        
        $this->id = $host_info['host_id'];
        $this->token = $host_info['host_token'];
        $this->parent_host_id = $host_info['parent_host_id'];
    }

    public function getHostConfig() {
        
    }

    public function getDomain() {
        return $this->domain;
    }
    
    public function getId() {
        
    }
    
    
    public function getParentHostId() {
        
    }
    
    
    
} 

