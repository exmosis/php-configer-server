<?php

class ConfigOutputFileManager {

    protected $db_connection;

    public function __construct(MySqlConnection $db_connection) {
        $this->db_connection = $db_connection;
    }
    
    public function getAllOutputFiles() {
        
        $results = $this
                    ->db_connection
                    ->link
                    ->select(
                        ConfigOutputFile::DB_TABLE__CONFIG_OUTPUT_FILES,
                        array(
                            'config_output_file_id',
                            'file_name',
                            'file_path',
                            'file_type'
                        )
                    );
                    
        if (count($results) == 0) {
            throw new Exception('Couldn\'t find any config output files in the database.');
        }
    
        $file_info = array();
    
        foreach ($results as $result) {
                
            $config_output_file_id = $result['config_output_file_id'];
            
            // Create new ConfigOutputFile object
            $config_output_file = new ConfigOutputFile(
                $config_output_file_id,
                $result['file_name'],
                $result['file_path'],
                $result['file_type']
            );
            
            $file_info[$config_output_file_id] = $config_output_file;
        }
        
        return $file_info;
        
    }
    
}
