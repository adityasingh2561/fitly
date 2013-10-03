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
			$this->expires = date('m/d/Y', $row["expires"]);
			mysql_close();
			return true;
		} else {
			return false;
		}
	}
	
	public function load() {
		if (empty($this->ticket) || trim($this->ticket) === "") {
			return false;
		}
		$sql = "select * from authtickets where ticket=" . mysql_real_escape_string($this->ticket);
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		if ( mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			$this->userid = $row["userid"];
			$this->ticket = $row["ticket"];
			$this->expires = date('m/d/Y', $row["expires"]);
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
		$now = new DateTime();
		if (!empty($this->expires) && $this->expires !== "") {
			if ($now <= $this->expires) {
				return true;
			} else {
				delete();
			}
		}
		return false;
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
		}
		$ticket = $this->enc->getToken();
		$expires = date_add("today +12 hours");
		$mysqldate = date("m/d/y g:i A", $expires);
		$sql = "insert into authtickets (userid, ticket,expires) values (";
		$sql .= $this->userid . ",'";
		$sql .= "'" . $ticket ."','";
		$sql .= "'" . $mysqldate ."')";
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		mysql_close();
		return true;
	}
}

?>
