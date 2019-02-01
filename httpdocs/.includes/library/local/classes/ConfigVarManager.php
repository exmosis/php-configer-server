<?php

/**
 * Class for loading ConfigVars - but not HostConfig Values themselves.
 */
class ConfigVarManager {

    protected $db_connection;
    protected $config_vars;
    protected $config_var_file_info;

    public function __construct(MySqlConnection $db_connection) {
        $this->db_connection = $db_connection;
        $this->config_vars = array();
        $this->config_var_file_info = null;
    }

    public function getForIds(array $config_var_ids) {
       
        $config_vars = array();
       
        if (empty($config_var_ids)) {
            return $config_vars;
        }
        
        // Make sure they're ints 
        $config_var_ids = array_map('intval', $config_var_ids);
        
        $config_var_info = $this
                        ->db_connection
                        ->link
                        ->select(
                            ConfigVar::DB_TABLE__CONFIG_VARS,
                            array(
                                'config_var_id',
                                'config_section_name',
                                'config_output_file_id',
                                'config_var_name',
                                'add_quotes',
                                'comment',
                                'allow_client_override'
                            ),
                            array(
                                'config_var_id' => $config_var_ids 
                            )
                        );
                        
        foreach ($config_var_info as $config_var) {
            
            $var = new ConfigVar(
                                $config_var['config_var_id'],
                                $config_var['config_var_name'],
                                $config_var['config_output_file_id'],
                                ($config_var['add_quotes']) ? true : false,
                                $config_var['comment'],
                                ($config_var['allow_client_override']) ? true : false
            );
            
            $this->config_vars[$config_var['config_var_id']] = $var;
            
        }
        
        return $this->config_vars;
        
    }

    private function fetchConfigVarFileInfo(ConfigOutputFileManager $config_output_file_manager) {
        
        $this->config_var_file_info = $config_output_file_manager->getAllOutputFiles();
        
        return $this->config_var_file_info;
        
    }
    
    public function getConfigVarsWithinFileInfo(ConfigOutputFileManager $config_output_file_manager) {
        
        // Load the output file info into this instance
        $file_info = $this->fetchConfigVarFileInfo($config_output_file_manager);
        
        $config_var_info = $this->config_vars;
       
        $results = array();        
        
        // Loop through our config vars and match them up to files
        /* @var $config_var ConfigVar */
        foreach ($config_var_info as $config_var) {
            
            $output_file_id = $config_var->getOutputFileId();
            if (! array_key_exists($output_file_id, $file_info)) {
                throw new Exception('Output file with ID ' . $output_file_id . ' couldn\'t be found. (Config Var ' . $config_var->getId() . ')');
            }
           
            /* @var $output_file ConfigOutputFile */ 
            $output_file = $file_info[$output_file_id];
            
            // Add file to results if not there yet
            if (! array_key_exists($output_file->getId(), $results)) {
                $results[$output_file->getId()] = array(
                    'file_info' => array (
                        // @TODO: Turn this into a function in ConfigOutputFile
                        'file_name' => $output_file->getFileName(),
                        'file_path' => $output_file->getFilePath(),
                        'file_type' => $output_file->getFileType()
                    ),
                    'config_vars' => array()
                );
            }
            
            // Add this config var to the array
            $results[$output_file->getId()]['config_vars'][$config_var->getId()] = array(
                // @TODO: Turn this into a function in ConfigVar
                'var_key' => $config_var->getVarKey(),
                'output_file_id' => $config_var->getOutputFileId(),
                'add_quotes' => $config_var->getAddQuotes(),
                'comment' => $config_var->getComment(),
                'allow_client_override' => $config_var->getAllowClientOverride()
            );
            
        }

        return $results;
        
    }
    
}
