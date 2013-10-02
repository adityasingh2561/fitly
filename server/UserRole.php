<?php
include_once 'config.php';
include_once 'Encryption.php';
include_once 'Role.php';
class UserRole {
	public $userid;
	public $roleid;
	public $description;
	public $role;
	
	public function getRole() {
		$sql = "select * from roles where id=" . $this->id;
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		while ($row = mysql_fetch_assoc($result)) {
			$r = new Role();
			$r->id = $row["id"];
			$r->name = $row["name"];
			$r->type = $row["type"];
			$this->role = $r;
		}
		mysql_close();
	}
	
	public function delete() {
		$sql = "delete from roles where id=" . $this->id;
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		mysql_close();
	}
	
	public function addOrUpdate() {
		$sql = "delete from roles where id=" . $this->id;
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$result = mysql_query($sql);
		$sql = "insert into userroles (userid,roleid,description) values(";
		$sql .= mysql_real_escape_string($this->userid) . ","; 
		$sql .= mysql_real_escape_string($this->roleid) . ",";
		$sql .= "'". mysql_real_escape_string($this->description) . "'";
		$sql .= " ) ";
		$result = mysql_query($sql);
		mysql_close();
		return true;
	}
}

?>
