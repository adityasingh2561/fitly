<?php
include_once './config.php';
include_once './User.php';
/*
include_once 'config.php';
include_once 'Encryption.php';
$encryption = new Encryption();
mysql_connect($db_host, $db_user, $db_password) or die(mysql_error()); 
mysql_select_db($db_name) or die(mysql_error());
$username="AdityaS";
$password="password";
$secretword1 = "secret1";
$secretword2 = "secret1";
$addUserSQL = "insert into users set username='". mysql_escape_string($username) . "', password='". $encryption->hash(mysql_escape_string($password)) ."', secretword1='". $encryption->hash(mysql_escape_string($secretword1)) ."' , secretword2='". $encryption->hash(mysql_escape_string($secretword2)) ."'";
$updateUserSQL = "update users set password='". $encryption->hash(mysql_escape_string($password)) ."', secretword1='". $encryption->hash(mysql_escape_string($secretword1)) ."', secretword2='". $encryption->hash(mysql_escape_string($secretword2))."' where username='" . mysql_escape_string($username) . "'";
$getUserSQL = "select * from users where username='" . mysql_escape_string($username) . "'";
$bFound = false;
$result = mysql_query("$getUserSQL");
if ( mysql_num_rows($result) ) {
	$bFound = true;
	mysql_query($updateUserSQL);
} else {
	mysql_query($addUserSQL);
}
*/
$user = new User();
if ($user->loadById(1)) {
	Print "Load successful\n";
} else {
	Print "Load Unsuccessful\n";
}
if ($user->loadByUserName("AdityaS")) {
	Print "User Loaded By Name\n";
	$user->debugDump();
	//$user->addOrUpdate("AdityaS", "passing", $user->secretword1, $user->secretword2);
	
	//$tik = $user->Login("AdityaS", "passing");
	//if ($tik !== false) {
		//Print "Login successful. Ticket = ". $user->debugDump() ."\n";
	//}
	if ($user->authtoken->isValid()) {
		Print "\ntoken is valid\n";
	}
}

?>
