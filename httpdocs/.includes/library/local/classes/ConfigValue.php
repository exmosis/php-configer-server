<?php

class ConfigValue {
        
    protected $config_var; 
    protected $value;
    
    public function __construct(ConfigVar $config_var, $value) {
        $this->config_var = $config_var;
        $this->value = $value;
    }
    
    public function getValue() {
        return $this->value;
    }
    
    public function getConfigVar() {
        return $this->config_var;
    }
    
}
