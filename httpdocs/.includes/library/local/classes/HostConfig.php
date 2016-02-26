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
     * @param String $host_domain  String  Domain to use for host - no http:// or trailing /
     * @param MySqlConnection $db_connection MySqlConnection MySQL Database Connection object to use  
     * @param String $access_token Optional access token, which will force a check on token when loading config
     */    
    public function __construct($host_domain, 
                                MySqlConnection $db_connection,
                                $access_token = null) {
        $this->domain = $host_domain;
        $this->db_connection = $db_connection;
        
        if (is_null($access_token)) {
            $this->fetchHostDetails();
        } else {
            $this->fetchHostDetailsUsingAccessToken($access_token);
        }
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
                        
        $this->setFromHostInfo($host_info[0]);
        
    }
    
    /**
     * Loads details using access token as double-check. 
     * 
     * @param String $access_token Token to use, cannot be null or empty
     * 
     * @throws Exception if access token is null or empty, or if couldn't load config 
     */
    public function fetchHostDetailsUsingAccessToken($access_token) {
        
        if (! $access_token) {
            throw new Exception('fetchHostDetailsUsingAccessToken() must be called with a token.');
        }
        
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
                                'AND' => array(
                                    'host_domain' => $this->getDomain(),
                                    'host_token' => $access_token
                                )
                            )
                        );
        
        if (count($host_info) == 0) {
            throw new Exception('Unable to find host details, either host domain or access token is wrong?');
        }   
                     
        $this->setFromHostInfo($host_info[0]);
        
    }
    
    private function setFromHostInfo($host_info) {
        $this->id = $host_info['host_id'];
        $this->token = $host_info['host_token'];
        $this->parent_host_id = $host_info['parent_host_id'];
    }

    public function getDomain() {
        return $this->domain;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getParentHostId() {
        return $this->parent_host_id;
    }
    
    
    
} 

