<?php

include_once 'config.php';
include_once 'Encryption.php';
class Role {
	public $id;
	public $name;
	public $type;
	
	public function load() {
		if (empty($this->id) || !isset($this->id) || trim($this->id) === "") {
			return false;
		}
		$sql = "select * from roles where id=" . $this->id;
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$this->id = $row["id"];
			$r->name = $row["name"];
			$r->type = $row["type"];
		}
		mysql_close();
	}
	
	public function debugDump() {
		Print "Name=" . $this->name . ", Type=" . $this->type . "\n";
	}
}

?>
