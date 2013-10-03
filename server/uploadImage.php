<?php
include_once 'config.php';
include_once 'Encryption.php';
$config = new config();
$response = array();
$allowedExts = array("gif", "jpeg", "jpg", "png");
$temp = explode(".", $_FILES["file"]["name"]);
$extension = end($temp);
$userid = $_REQUEST["userid"];
if ((($_FILES["file"]["type"] == "image/gif") || 
		($_FILES["file"]["type"] == "image/jpeg") || 
		($_FILES["file"]["type"] == "image/jpg") || 
		($_FILES["file"]["type"] == "image/pjpeg") || 
		($_FILES["file"]["type"] == "image/x-png") || 
		($_FILES["file"]["type"] == "image/png")) && 
		($_FILES["file"]["size"] < 20000) && 
		in_array($extension, $allowedExts)) {
	if ($_FILES["file"]["error"] > 0) {
		echo "Error: " . $_FILES["file"]["error"] . "<br>";
		$response["status"] = "error";
		$response["message"] = "Upload Failed. Perhaps the file was too large. Only pictures less 20 KB allowed.";
	} else {
		//echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		//echo "Type: " . $_FILES["file"]["type"] . "<br>";
		//echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		//echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		if (file_exists("upload/" . $_FILES["file"]["name"])) {
			echo $_FILES["file"]["name"] . " already exists. ";
		} else {
			move_uploaded_file($_FILES["file"]["tmp_name"], $config->getUploadLocation() . "/" . $userid . "." . $extension);
			//move_uploaded_file($_FILES["file"]["tmp_name"], $config->getUploadLocation() . "/" . $_FILES["file"]["name"]);
			//echo "Stored in: " . "upload/" . $_FILES["file"]["name"];
			//echo "Upload successful";
			$response["status"] = "success";
			$response["message"] = "Upload Succeeded";
		}
	}
} else {
	$response["status"] = "error";
	$response["message"] = "Upload Failed";
}
echo json_encode($response);
?> 
