<?php

require_once (fvSite::$fvConfig->get("path.entity") . 'user.class.php') ;

class UserManager extends fvRootManager {
	
	protected function __construct () {
	    $objectClassName = substr(__CLASS__, 0, -7);
	    
	    $this->rootObj = new $objectClassName();
	}
	
    static function getInstance()
    {
        static $instance; 
        if (!isset($instance)) {
            $instance = new self();
        }  
        return $instance;
    }
    
    /**
     * Function execute login into system. Return instance of logged user in success, or false otherwise.
     * 
     * Function retrieve $password field as md5 hash of password. 
     *
     * @param String $login
     * @param String $password
     * @return User
     */
    public function Login($login, $password) {
        
        $User = $this->getAll("login = '$login' AND password = '$password'");
                    
        if (count($User) == 1) $User = $User[0];
        else return false;
        
        if ($User instanceof User)
            return $User;
        else return false;
    }
}
