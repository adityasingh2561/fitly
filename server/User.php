<?php

include_once 'config.php';
include_once 'Encryption.php';
include_once 'UserInfo.php';
include_once 'UserSecrets.php';
include_once 'UserRole.php';
include_once 'Role.php';
include_once 'AuthTicket.php';
class User {
	public $id;
	public $username;
	public $password;
	public $secretword1;
	public $secretword2;
	public $userInfo;
	public $userSecrets;
	public $roles;
	public $authtoken;
	private $config;
	private $enc;
	
	function __construct() {
		$this->userSecrets = new UserSecrets();
		$this->config = new config();
		$this->enc = new Encryption();
		$this->userInfo = new UserInfo();
		$this->roles = new ArrayObject();
		$this->authtoken = new AuthTicket();
		
		//$this->config->debugDump();
	}
	
	public function loadById($id) {
		$sql = "select * from users where id=" . trim(mysql_real_escape_string($id));
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			$this->id = $row["ID"];
			$this->username = $row["username"];
			$this->password = $row["password"];
			$this->secretword1 = $row["secretword1"];
			$this->secretword2 = $row["secretword2"];
			mysql_close();
			$this->userInfo->loadByUserId(trim(mysql_real_escape_string($id)));
			$this->authtoken->userid = $this->id;
			$this->authtoken->loadByUserId();
			return true;
		} else {
			return false;
		}
		
	}
	
	public function loadByUserName($username) {
		$sql = "select * from users where TRIM(LOWER(username))='" . trim(strtolower(mysql_real_escape_string($username))) . "'";
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			$this->id = $row["ID"];
			$this->username = $row["username"];
			$this->password = $row["password"];
			$this->secretword1 = $row["secretword1"];
			$this->secretword2 = $row["secretword2"];
			mysql_close();
			$this->userInfo->loadByUserId(trim(mysql_real_escape_string($this->id)));
			$this->authtoken->userid = $this->id;
			$this->authtoken->loadByUserId();
			return true;
		} else {
			return false;
		}
		
	}
	
	public function addOrUpdate($username, $password, $secretword1, $secretword2) {
		$sql = "";
		$pwd = $this->enc->hash(mysql_real_escape_string($password));
		$s1 = $this->enc->hash(mysql_real_escape_string($secretword1));
		$s2 = $this->enc->hash(mysql_real_escape_string($secretword2));
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		if ($this->loadByUserName($username)) {
			if (empty($secretword1) || is_null($secretword1) ) {
				$secretword1 = "";
			}
			if (empty($secretword2) || is_null($secretword2)) {
				$secretword1 = "";
			}
			// update
			$sql = "update users set password='" . $pwd . "', secretword1='" . $s1 . "', secretword2='" . $s2 . "' where TRIM(LOWER(username))='" . trim(strtolower(mysql_real_escape_string($username))) . "'";
		} else {
			// add
			$sql = "insert into users (username, password,secretword1,secretword2) values ('". trim(strtolower(mysql_real_escape_string($username))) ."','". $pwd ."','". $s1 ."','". $s2 ."');";
		}
		mysql_query($sql);
		mysql_close();
		// reload
		$this->loadByUserName($username);
	}
	
	private function loadRoles() {
		$sql = "select * from userroles where userid=" . $this->id;
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$r = new UserRole();
			$r->userid = $row["userid"];
			$r->roleid = $row["roleid"];
			$r->description = $row["description"];
			$this->roles->append($r);
		}
		mysql_close();
	}
	
	public function deleteById($id) {
		
	}
	
	public function isLoggedIn() {
		
	}

	public function isAuthticketValid() {
		if (empty($this->authtoken) || trim($this->authtoken) === "") {
			return false;
		} else {
			$tik = new AuthTicket();
			$tik->userid = $this->id;
			if ($tik->load()) {
				if ($tik->isValid()) {
					return true;
				}
			}
		}
		return false;
	}
	
	public function Login($username, $password) {
		$password = trim(mysql_real_escape_string($password));
		//Print "Login: Password=" . $password . "\n";
		//Print "Hash=" . $this->enc->hash($password) . "\n";
		if (!empty($this->authtoken)) {
			if ($this->authtoken->isValid()) {
				return true;
			}
		} else {
			//Print "No ticket\n";
		}
		if ($this->loadByUserName($username)) {
			Print "Password in DB=" . $this->password ."\n";
			if ($this->enc->hash($password) === $this->password) {
				$this->authtoken->userid = $this->id;
				$this->authtoken->delete();
				$this->authtoken->create();
				return true;
			} else {
				return false;
			}
		}
	}
	
	public function doesSecretWordMatch($secretword) {
		
	}
	
	public function Logout() {
		
	}
	
	public function getDataArray() {
		$dump = array();
		$dump["userid"]= $this->id;
		$dump["username"] = $this->username;
		$dump["passwordhash"] = $this->password;
		$dump["secretword1hash"] = $this->secretword1;
		$dump["secretword2hash"] = $this->secretword2;
		$userInfoDump = $this->userInfo->getDataArray();
		$authTokenDump = $this->authtoken->getDataArray();
		return array_merge($dump,$userInfoDump,$authTokenDump);
		
	}
	
	public function debugDump() {
		Print json_encode($this->getDataArray()) . "\n";
	}
}
?>
