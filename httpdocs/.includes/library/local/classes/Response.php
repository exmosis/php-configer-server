<?php

/**
 * A general response object. Must be created with init(), accessed via Response::getInstance() 
 */
abstract class Response {
    
    private $success;
    private $messages;
    private $errors;

    // somewhere to store attributes of the response
    protected $metadata;

    protected static $instance;

    /**
     * Non-static init method, called on the static instance member, to initialise private fields
     */
    protected function init() {
                    
        $this->success = true;       
        $this->messages = array();
        $this->headers = array();
        $this->errors = array();

        $this->metadata = array();

    }

    /**
     * Static method to return the static instance of the response object. Creates the static instance if one does not yet exist.
     * 
     * @return Response object of type of calling class
     */    
    public static function getInstance() {
        if (! static::$instance) {

            $class = __CLASS__;
            static::$instance = new static();
            
        }
        
        return static::$instance;
    }

    public function __construct() {
        $this->init();
    }

    /**
     * Static helper function to check what singleton already exists. Will return the class name if so, or null if not.
     * 
     * @return Mixed    String of class name if already exists, or null if not existing yet
     */
    public static function getPossibleCurrentInstance() {
        if (static::$instance) {
            return get_class(static::$instance);
        }
        
        return null;
    }

    public function isSuccess() {
        return $this->success;
    }

    public function setSuccess($success) {
        $this->success = (bool) $success;
    }
    
    public function addMessage($msg) {
        $this->messages[] = $msg;
    }
    
    public function getMessages() {
        return $this->messages;
    }

    public function setBody($body){
        $this->body = $body;
    }

    public function getBody(){
        return $this->body;
    }


    /**
     * Set up a change logger
     * 
     * @param DatabaseObjectChangeLog $change_log DatabaseObjectChangeLog to use
     */
    public function setDatabaseObjectChangeLog(DatabaseObjectChangeLog $change_log) {
        $this->bdo_change_log = $change_log;
    }

    public function getDatabaseObjectChangeLog() {
        return $this->bdo_change_log;
    }
    
    /**
     * Adds or replaces a key/value pair of metadata to be provided to the client (not necessarily user)
     * @param string $key   The key of the metadata obj
     * @param string $value The value of the metadata object
     */
    public function addMetadata($key, $value) {
        $this->metadata[$key] = $value;
    }

    public function getMetadata($key) {
        return $this->metadata[$key];
    }

    public function removeMetadata($key) {
        unset($this->metadata[$key]);
    }

    /**
     * Adds the specified error to the errors, sets success to false
     * 
     * @param string $err_msg the error message to add
     */
    public function addError($err_msg) {
        $this->errors[] = $err_msg;
        // Set success to false if errors present
        $this->setSuccess(false);
        $this->addHeader("status","HTTP/1.1 500 Internal Server Error");
    }
    
    public function getErrors() {
        return $this->errors;
    }


    
    protected function asArray($extra = array()) {
        $response_array = array();
        
        // Build up array of response contents - success only at first
        $response_array['success'] = $this->isSuccess();
        $response_array['messages'] = $this->getMessages();
        $response_array['errors'] = $this->getErrors();

        // Don't return body if we weren't successful
        if(isset($this->body) && $this->isSuccess()) {
            $response_array['body'] = $this->getBody();
            if (isset($this->bdo_change_log)) {
                // add related object changes if any found
                // TODO : Move session set up further up
                $response_array['session'] = array();
                $response_array['session']['object_changes'] = $this->bdo_change_log->getChanges();
            }
        }
        
        return array_merge($response_array, $extra);
        
    }
    
    abstract public function go(); 

}

