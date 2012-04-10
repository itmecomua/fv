<?php
	/** $Id: session_manager.class.php,v 1.8 2005/07/09 15:12:25 tonkov Exp $
	* Session handler functions
	* NOTE: This is `static-singleton` class
	* start Tue Oct 26 15:42:28 EEST 2004 @571 /Internet Time/
	* @author Voituk Vadim <voituk@asg.kiev.ua>
	* @package classes
	*
	* [sql]
		CREATE TABLE `only_session` (
			`sess_id` varchar(255) NOT NULL default '',
			`last_updated` int(11) NOT NULL default '0',
			`value` text,
			PRIMARY KEY  (`sess_id`),
			KEY `LastUpdated` (`last_updated`)
		) TYPE=MyISAM;
		[/sql]
	*/
	
	// if this class 
	if (!defined('_SESSION_MANAGER_INCLUDED_')) {
		
		define('_SESSION_MANAGER_INCLUDED_', 1);
		
		/* Session manager class */
		class fvSession {
			
			/** Session lifetime  */
			private $LIFETIME;
			
			private $TABLE_NAME;
			
			private $SESS_NAME;
			
			private $_USER_NAME;
			
			/** Constructor */
			function __construct($SESS_NAME='fv_session', $LIFETIME=1800, $TABLE_NAME = "fv_session", $USER_NAME = "login/loggedUser") {
			    
			    $this->SESS_NAME = $SESS_NAME;
			    $this->_USER_NAME = $USER_NAME;
			    
			    if (fvSite::$fvConfig->get("session.use_database", true)) {
				    $this->LIFETIME = $LIFETIME;
				
				    session_set_save_handler(
						array(& $this, 'sess_open'), 
						array(& $this, 'sess_close'), 
						array(& $this, 'sess_read'), 
						array(& $this, 'sess_write'), 
						array(& $this, 'sess_destroy'), 
						array(& $this, 'sess_gc')
					);
					
					$this->TABLE_NAME = $TABLE_NAME;
				}
				else {
				    session_name($this->SESS_NAME);    
				}
			}
			
			/** Start session engine */
			function start() {
				//if (empty(session_id())) {
					session_name($this->SESS_NAME);
					session_start();
				//}
			}
			
			/** Stop session engine */
			function stop() {
				session_destroy();
			}
			
			/** Set session value */
			function set($key, $value) {
				global $_SESSION;
				$_SESSION[$key] = $value;
			}
			
			/** Get session value */
			function get($key) {
				global $_SESSION;
				return $_SESSION[$key];
			}
			
			/** Unset session value */
			function remove($key) {
				global $_SESSION;
				unset($_SESSION[$key]);
			}
			
			/** Clear all session variables  */
			function clear() {
				global $_SESSION;
				foreach ($_SESSION as $key => $value)
					unset($_SESSION[$key]);
			}
			
			/** Find out whether a global variable is registered in a session  */
			function is_set($key) {
				global $_SESSION;
				return isset($_SESSION[$key]);
			}
			
			function getUser() {
			    return $this->get($this->_USER_NAME);
			}
			
			function setUser($user) {
			    return $this->set($this->_USER_NAME, $user); 
			}
			
			function finish() {
				session_write_close();
			}
			
			/* --- session handling methods --- */
			
			/** Session open method */
			function sess_open() {
				$this->sess_gc($this->LIFETIME);
				return true;
			}
			
			/* Session close method **/
			function sess_close() {
				return true;
			}
			
			/** Session read method */
			function sess_read($key) {
			    $res = fvSite::$DB->getOne("SELECT value FROM " . $this->TABLE_NAME . " WHERE sess_id = ?", $key);
			    
			    if (fvSite::$DB->lastSelectCount) {
			        return $res; 
			    } else {
			        
			        $sth = fvSite::$DB->prepare("INSERT INTO ".$this->TABLE_NAME." (sess_id, last_updated, value) VALUES(?, UNIX_TIMESTAMP(NOW()), '')");
			        fvSite::$DB->execute($sth ,$key);
			        fvSite::$DB->freePrepared($sth);
			        
			        return '';
			    }
			    
			}
			
			/** Write session data */
			function sess_write($key, $value) {
			    
			    $sth = fvSite::$DB->prepare("UPDATE " . $this->TABLE_NAME . " SET value = ?, last_updated=UNIX_TIMESTAMP(NOW()) WHERE sess_id = ?");
			                
			    fvSite::$DB->execute($sth, array($value, $key));
			    fvSite::$DB->freePrepared($sth);
			    			    
			}
			
			/** Destroy session */
			function sess_destroy($key) {
			    
			    $sth = fvSite::$DB->prepare("DELETE FROM " . $this->TABLE_NAME . " WHERE sess_id = ?");
			    fvSite::$DB->execute($sth, $key);
			    fvSite::$DB->freePrepared($sth);
			    
			}
			
			/** Session garbage collection */
			function sess_gc($lifetime) {

			    $sth = fvSite::$DB->prepare("DELETE FROM " . $this->TABLE_NAME . " WHERE UNIX_TIMESTAMP(NOW())-last_updated > ?");
			    fvSite::$DB->execute($sth, $lifetime);
			    fvSite::$DB->freePrepared($sth);

			}
			
			// to be continued...
		}//class
		
	} //if (!defined('_SESSION_MANAGER_INCLUDED_'))
?>
