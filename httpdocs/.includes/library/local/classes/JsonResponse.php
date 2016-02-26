<?php

class JsonResponse extends HttpResponse {
    
    /**
     * Gets a JSON encoded summary of this response object.
     * 
     * @return::string JSON the summary of this response
     */
    public function asJson($extra = array()) {
        return json_encode($this->asArray($extra));
    }
    
    public function go() {
        $this->executeHeaders();
        echo $this->asJson();
    }    
    
}
