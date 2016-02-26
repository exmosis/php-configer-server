<?php

/**
 * Abstraction layer between the code and the HTTP request received by PHP
 *
 * Ideally would move this to an external library such as Unirest (http://unirest.io/php.html)
 * but currently supporting a need to support PHP 5.3 :-/
 */
class HttpRequest {

	const POST = "post";
	const GET = "get";
	
    const ERROR_MSG__MISSING_HTTP_PARAMETERS = "Missing required HTTP parameters";
    const ERROR_MSG__INVALID_HTTP_METHOD = "Invalid HTTP method requested";
    
    /**
     * Constructor adds a required_fields array which throws an exception if we haven't
     * received the fields anywhere.
     * 
     * @param array $required_fields Optional array of required field names, which may appear in GET or POST
     * 
     * @throws Exception if any required fields are missing 
     */
    public function __construct(array $required_fields = null) {
        
        if (! is_null($required_fields)) {
            
            $missing_fields = array();
            
            foreach ($required_fields as $required_field) {
                if (! $this->hasParameter($required_field)) {
                    $missing_fields[] = $required_field;
                }
            }
            
            if (count($missing_fields) > 0) {
                $missing_fields = implode(', ', $missing_fields);
                throw new Exception(self::ERROR_MSG__MISSING_HTTP_PARAMETERS . ': ' . $missing_fields);
            }
            
        }
        
    }
    
    public function hasParameter($name, $method = null) {

        if ($method !== self::POST && 
            $method !== self::GET &&
            ! is_null($method)) {
                
                // trying to ask for something we can't give. throw a tantrum
                throw new Exception(self::ERROR_MSG__INVALID_HTTP_METHOD . ': ' . $method);
                
            }  

        if ($method == self::POST) {
            return (array_key_exists($name, $_POST));
        } else if ($method == self::GET) {
            return (array_key_exists($name, $_GET));
        } else {
            return (array_key_exists($name, $_POST) ||
                    array_key_exists($name, $_GET));
        }

    }
    
	/**
	 * Get a parameter from the request. If $method is null, POST is preferred over GET.
     * 
     * If the parameter doesn't exist in the requested scope, returns null
     * 
	 * @param  string  $name   Parameter name
	 * @param  string|null $method The method (Request::GET, Request::POST)
     * 
	 * @return mixed          Returns the value in the request or null if not found
     * 
     * @throws Exception if a method other than null, GET or POST is asked for
	 */
	public function getParameter($name, $method = null) {

        if ($method !== self::POST && 
            $method !== self::GET &&
            ! is_null($method)) {
                
                // trying to ask for something we can't give. throw a tantrum
                throw new Exception(self::ERROR_MSG__INVALID_HTTP_METHOD . ': ' . $method);
                
            }

		if(is_null($method)) {

			// return a parameter, prioritising post
			if (isset($_POST[$name])) {
			    return $_POST[$name];
			} else if (isset($_GET[$name])) {
			    return $_GET[$name];
			}
            return null;

		} else if(strtolower($method) == self::GET) {

			// return the named get parameter
			if(array_key_exists($name, $_GET)) return $_GET[$name];
			else return null;

		} else if(strtolower($method) == self::POST) {

			// return the named post parameter
			if(array_key_exists($name, $_POST)) return $_POST[$name];
			else return null;

		}
        
	}

}
	