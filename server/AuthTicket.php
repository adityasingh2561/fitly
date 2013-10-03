<?php
date_default_timezone_set('America/Los_Angeles');
include_once 'config.php';
include_once 'Encryption.php';
include_once 'UserInfo.php';
include_once 'UserSecrets.php';
include_once 'UserRole.php';
include_once 'Role.php';
class AuthTicket {
	public $userid;
	public $ticket;
	public $expires;
	private $config;
	private $enc;
	
	function __construct() {
		$this->config = new config();
		$this->enc = new Encryption();
	}
	
	public function loadByUserId() {
		if (empty($this->userid) || trim($this->userid) === "") {
			return false;
		}
		$sql = "select * from authtickets where userid=" . mysql_real_escape_string($this->userid);
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			$this->ticket = $row["ticket"];
			$this->expires = date('m/d/Y H:i:s', strtotime($row["expires"]));
			mysql_close();
			return true;
		} else {
			return false;
		}
	}
	
	public function load() {
		if (empty($this->ticket) || trim($this->ticket) === "") {
			Print "No ticket\n";
			return false;

		}
		$sql = "select * from authtickets where ticket=" . mysql_real_escape_string($this->ticket);
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		if ( $result !== false) {
			$row = mysql_fetch_array($result);
			$this->userid = $row["userid"];
			$this->ticket = $row["ticket"];
			$this->expires = date('m/d/Y H:i:s', strtotime($row["expires"]));
			mysql_close();
			return true;
		} else {
			return false;
		}
	}
	
	
	public function isValid() {
		if (empty($this->userid) || trim($this->userid) === "") {
			return false;
		}
		$now = date('m/d/Y H:m:s');
		if (!empty($this->expires) && $this->expires !== "") {
			//Print "Now =" . $now;
			Print "Expires =" . $this->expires;
			if ($now <= $this->expires) {
				//Print "Token has not expired\n";
				return true;
			} else {
				//Print "Token is not valid\n";
				return false;
			}
		}
		//Print "Date Time checks failed\n";
	}
	public function delete() {
		if (empty($this->userid) || trim($this->userid) === "") {
			return false;
		}
		
		$sql = "delete from authtickets where userid=" . mysql_real_escape_string($this->userid);
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		mysql_close();
		return true;
	}
	public function create() {
		if (empty($this->userid) || trim($this->userid) === "") {
			return false;
			Print "No user Id\n";
		}
		$ticket = $this->enc->getToken();
		$this->ticket = $ticket;
		Print "Token = " . $ticket . "\n";
		$now = new DateTime();
		$expires = date_add($now,new DateInterval("PT12H"));
		$mysqldate = $expires->format("Y-m-d H:i:s");
		$sql = "insert into authtickets (userid, ticket,expires) values (";
		$sql .= $this->userid . ",";
		$sql .= "'" . $ticket ."',";
		$sql .= "'" . $mysqldate ."')";
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		//Print "SQL = " . $sql . "\n";
		$result = mysql_query($sql);
		mysql_close();
		//Print "Auth ticket created\n";
		$this->load();
		return true;
	}
	
	public function debugDump() {
		Print json_encode($this->getDataArray()) . "\n";
	}
	
	public function getDataArray() {
		$dump = array();
		$dump["ticket"] = $this->ticket;
		$dump["expires"] = $this->expires;
		return $dump;
	}
}

?>
