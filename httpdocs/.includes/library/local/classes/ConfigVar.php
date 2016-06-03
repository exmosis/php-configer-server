<?php

/**
 * A particular key that may be attached to a ConfigValue. Includes info on output file, comment, etc.
 */
class ConfigVar {
    
    const DB_TABLE__CONFIG_VARS = "config_vars";
        
    protected $id = null;    
    
    protected $var_key = null;
    protected $output_file_id = null;
    protected $add_quotes = false;
    protected $comment = null;
    protected $allow_client_override = false;
    
    public function __construct(
                                $id,
                                $key,
                                $output_file_id,
                                $add_quotes = null,
                                $comment = null,
                                $allow_client_override = null
                               ) {

        $this->id = $id;
        $this->var_key = $key;
        $this->output_file_id = $output_file_id;
        
        if (!is_null($add_quotes) && is_bool($add_quotes)) {
            $this->add_quotes = $add_quotes;
        }
        
        if (! is_null($comment)) {
            $this->comment = $comment;
        }
        
        if (!is_null($allow_client_override) && is_bool($allow_client_override)) {
            $this->allow_client_override = $allow_client_override;
        }

    }
                               
    public function getId() {
        return $this->id;
    }
    
    public function getVarKey() {
        return $this->var_key;
    }
    
    public function getOutputFileId() {
        return $this->output_file_id;
    }
    
    public function getAddQuotes() {
        return $this->add_quotes;
    }
    
    public function getComment() {
        return $this->comment;
    }
    
    public function getAllowClientOverride() {
        return $this->allow_client_override;
    }
    
}
