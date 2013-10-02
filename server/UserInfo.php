<?php
include_once 'config.php';
include_once 'Encryption.php';
class UserInfo {
	public $id;
	public $userid;
	public $address1;       
	public $address2;       
	public $city;           
	public $state;          
	public $zip;            
	public $phone;          
	public $email;          
	public $cellphone;      
	public $pic;            
	public $introduction;   
	public $firstname;      
	public $lastname;       
	public $gender;         
	public $weight;         
	public $height;         
	public $medicalhistory;
	private $config;

	function __construct() {
		$this->config = new config();
	}
	
	

	public function load() {
		loadByUserId($this->userid);
	}
	public function loadByUserId($userid) {
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$sql = "select * from userinfobasic where userid=" . trim(mysql_real_escape_string($userid));
		$result = mysql_query($sql);
		if ( $result && mysql_num_rows($result) > 0) {
			$row = mysql_fetch_array($result);
			$this->id = $row["ID"];
			$this->userid = $row["userid"];
			$this->address1 = $row["address1"];
			$this->address2 = $row["address2"];
			$this->city = $row["city"];
			$this->state = $row["state"];
			$this->zip = $row["zip"];
			$this->phone = $row["phone"];
			$this->email = $row["email"];
			$this->cellphone = $row["cellphone"];
			$this->pic = $row["pic"];
			$this->introduction = $row["introduction"];
			$this->firstname = $row["firstname"];
			$this->lastname = $row["lastname"];
			$this->gender = $row["gender"];
			$this->gender = $row["gender"];
			$this->weight = $row["weight"];
			$this->height = $row["height"];
			$this->medicalhistory = $row["medicalhistory"];
			return true;
		} else {
			return false;
		}
		
		mysql_close();
	}
	
	public function addOrUpdate() {
		if (empty($this->userid) || !isset($this->userid) || trim($this->userid) === "" || $this->userid === 0) {
			return false;
		}
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$sql = "delete from userinfobasic where userid=" . trim(mysql_real_escape_string($this->userid));
		$result = mysql_query($sql);
		$sql = "insert into userinfo (userid,address1,address2,city,state,zip,phone,email,cellphone,pic,introduction,firstname,lastname,gender,weight,height,medicalhistory) values (";
		$sql .= trim(mysql_real_escape_string($this->userid)) . ",";
		$sql .= "'" . trim(mysql_real_escape_string($this->address1)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->address2)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->city)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->state)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->zip)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->phone)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->email)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->cellphone)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->pic)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->introduction)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->firstname)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->lastname)) ."',";
		$sql .= "'" . trim(mysql_real_escape_string($this->gender)) ."',";
		$sql .= trim(mysql_real_escape_string($this->weight)) .",";
		$sql .= trim(mysql_real_escape_string($this->height)) .",";
		$sql .= "'" . trim(mysql_real_escape_string($this->medicalhistory)) ."'";
		$sql .= " ) ";
		$result = mysql_query($sql);
		mysql_close();
		return true;
	}
	
	public function delete() {
		if (empty($this->userid) || !isset($this->userid) || trim($this->userid) === "" || $this->userid === 0) {
			return false;
		}
		mysql_connect($this->config->db_host, $this->config->db_user, $this->config->db_password) or die(mysql_error()); 
		mysql_select_db($this->config->db_name) or die(mysql_error());
		$sql = "delete from userinfobasic where userid=" . trim(mysql_real_escape_string($this->userid));
		$result = mysql_query($sql);
		mysql_close();
		return true;

	}
	
	public function debugDump() {
		$dump = "";
		$dump .= "Address1= " . $this->address1 .",";
		$dump .= "Address2= " . $this->address2 .",";
		$dump .= "City= " . $this->city .",";
		$dump .= "State= " . $this->state .",";
		$dump .= "Zip= " . $this->zip .",";
		$dump .= "Phone= " . $this->phone .",";
		$dump .= "Email= " . $this->email .",";
		$dump .= "Cellphone= " . $this->cellphone .",";
		$dump .= "Pic= " . $this->pic .",";
		$dump .= "Intro= " . $this->introduction .",";
		$dump .= "FirstName= " . $this->firstname .",";
		$dump .= "LastName= " . $this->lastname .",";
		$dump .= "Gender= " . $this->gender .",";
		$dump .= "Wt= ".$this->weight .",";
		$dump .= "Ht= ".$this->height .",";
		$dump .= "Med Hist= " . $this->medicalhistory ."\n";
		Print $dump;
	}
}
?>
