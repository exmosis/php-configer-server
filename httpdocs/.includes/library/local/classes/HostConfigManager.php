<?php

/**
 * Class which would otherwise be a lot of static functions for dealing with loading 
 * HostConfig objects.
 */
class HostConfigManager {
    
    private $target_host_config_id = null;
    private $target_host_config_access_token = null;
    private $target_host_config_domain = null;
    
    /* @var $db_connection MySqlConnection *.
    protected $db_connection = null;
    
    /**
     * Constructor to set up a HostConfigManager for loading a HostConfig by either
     * ID or domain.
     * 
     * @param Mixed $id_or_domain Pass in an int to load by ID, or a non-int to load by domain.
     * @param Mixed $access_token String to force security check when loading, or null to not check
     * @param MySqlConnection $db_connection Database connection to use
     */
    public function __construct($id_or_domain, $access_token, MySqlConnection $db_connection) {
        
        if (is_int($id_or_domain)) {
            $this->target_host_config_id = (int) $id_or_domain;
            $this->target_host_config_domain = null;
        } else {
            $this->target_host_config_id = null;
            $this->target_host_config_domain = $id_or_domain;
        }
        
        if (! is_null($access_token)) {
            $access_token = trim($access_token);
        }
        $this->target_host_config_access_token = $access_token;
        
        $this->db_connection = $db_connection;
        
    }

    /**
     * Gets the HostConfig object for the given setup if we can.
     * 
     * Doesn't support ID loading yet, needs a quick translation to domain.
     * 
     * @return HostConfig HostConfig class, or null if not loaded
     * 
     * @throws Exception if trying to load by ID
     */
    public function getHostConfig() {
        
        if (! is_null($this->target_host_config_domain)) {
            $host_config = new HostConfig(
                                          $this->target_host_config_domain, 
                                          $this->db_connection,
                                          $this->target_host_config_access_token
                                         );
            return $host_config;
        } else {
            throw new Exception('Sorry, loading HostConfigs by ID isn\'t supported yet.');
        }
    }
   
}
