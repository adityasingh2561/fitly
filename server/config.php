<?php
$env = getenv("FITLY_ENV");
date_default_timezone_set('America/Los_Angeles');
$db_host = "";
$db_user = "";
$db_password = "";
$link = null;
if (empty($env)) {
	$env = "production";
} else if (stristr($env, "dev")){
	$env = "development";
}

if ($env === "production") {
	$db_host = "";
	$db_user = "";
	$db_password = "";
	error_reporting(E_ERROR);
} else {
	$db_host = "";
	$db_user = "";
	$db_password = "";
	error_reporting(E_ALL);
}

?>
