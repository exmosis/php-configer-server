<?php

class ConfigOutputFile {
    
    const DB_TABLE__CONFIG_OUTPUT_FILES = "config_output_files";
    
    protected $id;
    protected $file_name;
    protected $file_path;
    protected $file_type;
    
    public function __construct($id, $file_name, $file_path, $file_type) {
        $this->id = $id;
        $this->file_name = $file_name;
        $this->file_path = $file_path;
        $this->file_type = $file_type;
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getFileName() {
        return $this->file_name;
    }
    
    public function getFilePath() {
        return $this->file_path;
    }
    
    public function getFileType() {
        return $this->file_type;
    }
    
}
