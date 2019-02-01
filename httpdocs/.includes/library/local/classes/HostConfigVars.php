<?php

/**
 * Vars and Values for a specific HostConfig
 */
class HostConfigVars {

    const DB_TABLE__HOST_CONFIG_VALUES = 'host_config_values';
    
    protected $db_connection = null;
    protected $config_var_manager = null;

    protected $host_id;
    protected $parent_host_domain;
    protected $config_vars;

    /**
     * @param Int   $id ID of host config var to load from Database
     */
    public function __construct($host_id, $parent_host_domain, MySqlConnection $db_connection, ConfigVarManager $config_var_manager) {
        
        $this->setHostId($host_id);
        $this->parent_host_domain = $parent_host_domain;
        $this->config_vars = array();
        $this->db_connection = $db_connection;
        $this->config_var_manager = $config_var_manager;
        
        $this->fetchHostConfigValues();
    }
    
    public function setHostId($host_id) {
        $this->host_id = $host_id;
    }
    
    public function getHostId() {
        return $this->host_id;
    }
    
    public function getParentHostDomain() {
        return $this->parent_host_domain;
    }
    
    public function getConfigVars() {
        return $this->config_vars;
    }
    
    protected function fetchHostConfigValues() {
        
        $id = $this->getHostId();
        
        $host_config_value_info = $this
                        ->db_connection
                        ->link
                        ->select(
                            self::DB_TABLE__HOST_CONFIG_VALUES,
                            array(
                                'config_var_id',
                                'config_var_value',
                            ),
                            array(
                                'host_id' => $this->getHostId()
                            )
                        );
                        
        $config_var_ids = array();
        foreach ($host_config_value_info as $host_config_value) {
            $config_var_ids[] = $host_config_value['config_var_id'];
        }

        $supporting_config_vars = $this->config_var_manager->getForIds($config_var_ids);
            
        $this->setFromHostConfigValueInfo($host_config_value_info, $supporting_config_vars);
        
    } 

    /**
     * This will reset and set our $config_vars array based on the incoming info + any supporting info
     */
    protected function setFromHostConfigValueInfo($host_config_value_info, array $reference_config_vars) {
       
        $this->config_vars = array();
        
        foreach ($host_config_value_info as $host_config_value) {
            $config_value = $host_config_value['config_var_value'];
            $config_var_id = $host_config_value['config_var_id'];
            
            if (! isset($reference_config_vars[$config_var_id])) {
                throw new Exception('setFromHostConfigValueInfo() cannot look up ConfigVar for ID ' . $config_var_id);
            }
            
            $config_value = new ConfigValue($reference_config_vars[$config_var_id], $config_value);
            
            $this->config_vars[] = $config_value;
        }
        
    }
    
    /**
     * Returns the config vars for this host nested inside the output file hierarchy details
     */
    public function getConfigVarsWithinFileInfo(ConfigOutputFileManager $config_output_file_manager) {
        
        $nested_config_vars = $this->config_var_manager->getConfigVarsWithinFileInfo($config_output_file_manager);
        $nested_config_vars = $this->populateConfigVarsWithValues($nested_config_vars);
        return $nested_config_vars;
        
    }
    
    // Traverses a tree of file-based config vars, and adds values based on the ID of the array compared to the IDs of the 
    // config values we know about.
    private function populateConfigVarsWithValues($nested_config_vars) {
        
        // Assemble list of values by config var ID
        $config_vals_by_id = array();
        /* @var $config_var ConfigValue */
        foreach ($this->config_vars as $config_var) {
            $config_vals_by_id[$config_var->getConfigVar()->getId()] = $config_var->getValue();
        }
        
        // Cycle through config vars and add value
        foreach ($nested_config_vars as $file_id => $file_info) {
            foreach ($file_info['config_vars'] as $var_id => $config_var_info) {
                if (array_key_exists($var_id, $config_vals_by_id)) {
                    $nested_config_vars[$file_id]['config_vars'][$var_id]['var_value'] = $config_vals_by_id[$var_id];
                }
            }
        }
        
        return $nested_config_vars;
        
    }
    
    
    
}
