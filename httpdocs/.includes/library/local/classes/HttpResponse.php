<?php

abstract class HttpResponse extends Response {

    protected $headers = array();

    /**
     * Add header
     * @param string $name  Name of the header ('status' for normal HTTP status)
     * @param string $value Value of the header
     */
    public function addHeader($name, $value){
        $this->headers[$name] = $value;
    }

    /**
     * Applies the given headers to the request
     */
    public function executeHeaders(){
        foreach($this->headers as $name => $header){
            if ($name == "status") {
                header($header); // status header has no prefix as is part of HTTP protocol
            } else {
                header($name . ": " . $header); // all headers have a name and a value
            }
        }
    }
    
}

