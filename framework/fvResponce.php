<?php

class fvResponce {
    protected $_headers;
    protected $_responceBody;
    protected $_statusText;
    protected $_status;
    protected $_useLayoult;
    
    protected function __construct() {
        $this->_headers = array();
        $this->_responceBody = '';
        $this->_statusText = array(
          '100' => 'Continue',
          '101' => 'Switching Protocols',
          '200' => 'OK',
          '201' => 'Created',
          '202' => 'Accepted',
          '203' => 'Non-Authoritative Information',
          '204' => 'No Content',
          '205' => 'Reset Content',
          '206' => 'Partial Content',
          '300' => 'Multiple Choices',
          '301' => 'Moved Permanently',
          '302' => 'Found',
          '303' => 'See Other',
          '304' => 'Not Modified',
          '305' => 'Use Proxy',
          '306' => '(Unused)',
          '307' => 'Temporary Redirect',
          '400' => 'Bad Request',
          '401' => 'Unauthorized',
          '402' => 'Payment Required',
          '403' => 'Forbidden',
          '404' => 'Not Found',
          '405' => 'Method Not Allowed',
          '406' => 'Not Acceptable',
          '407' => 'Proxy Authentication Required',
          '408' => 'Request Timeout',
          '409' => 'Conflict',
          '410' => 'Gone',
          '411' => 'Length Required',
          '412' => 'Precondition Failed',
          '413' => 'Request Entity Too Large',
          '414' => 'Request-URI Too Long',
          '415' => 'Unsupported Media Type',
          '416' => 'Requested Range Not Satisfiable',
          '417' => 'Expectation Failed',
          '500' => 'Internal Server Error',
          '501' => 'Not Implemented',
          '502' => 'Bad Gateway',
          '503' => 'Service Unavailable',
          '504' => 'Gateway Timeout',
          '505' => 'HTTP Version Not Supported',
        );
    }
    
    public function useLayoult($use = null) {
        if (!is_null($use)) {
            $this->_useLayoult = (bool)$use;
        }
        return $this->_useLayoult;
    }
    
    static public function getInstance() {
        static $instance;
        
        if (is_null($instance)) {
            $instance = new self();
        }
        
        return $instance;
    }
    
    public function setStatus($status) {
        $this->_status = $status;
    }
    
    public function setHeader ($header, $value) {
        $this->_headers[$header] = $value;
    }
    
    public function sendHeaders() {
        header("HTTP/1.0 " . $this->_status . " " . $this->_statusText[$this->_status]);
        foreach ($this->_headers as $header => $value) 
        {
            //var_dump($header.":".$value);
            header("{$header}: {$value}");
        }
    }
    
    public function clearHeaders() {
        $this->_headers = array();
    }
    
    public function setResponceBody($responceBody) {
        $this->_responceBody = $responceBody;
    }
    
    public function getResponceBody() {
        return $this->_responceBody;
    }
    
    public function sendResponceBody() {
        echo $this->getResponceBody();
    }
    
    public function setFlash($message, $type) {
        $this->setHeader("actionmessage", json_encode(array('message' => $message, 'type' => $type)));
    }
}
