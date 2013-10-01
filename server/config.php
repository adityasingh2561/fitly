<?php
date_default_timezone_set('America/Los_Angeles');
error_reporting(E_ALL);
class config {
	public $db_host;
	public $db_user;
	public $db_password;
	public $db_name;
	public function isProduction() {
		$env = getenv("FITLY_ENV");
		if (stristr($env, "prod")) {
			return true;
		} else {
			return false;
		}
	}
	
	function __construct() {
		$env = getenv("FITLY_ENV");
		if (stristr($env, "prod")) {
			$this->db_host = "localhost";
			$this->db_user = "fitly";
			$this->db_password = "F1tl3e";
			$this->db_name = "fitly";
		} else {
			$this->db_host = "localhost";
			$this->db_user = "fitly";
			$this->db_password = "F1tl3e";
			$this->db_name = "fitly";
		}
	}
	
	public function debugDump() {
		Print "db_host=". $this->db_host . "\n";
		Print "db_name=". $this->db_name . "\n";
		Print "db_user=". $this->db_user . "\n";
		Print "db_password=". $this->db_password . "\n";
	}
}

?>
