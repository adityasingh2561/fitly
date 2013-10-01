<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<link rel="stylesheet" type="text/css" href="styles/fitly.css">
		<script src="js/fitly.js"></script>
        <title>Test Encryption</title>
    </head>
    <body>
		<?php
		include_once("Encryption.php");
		$message = "";
		$encryption = new Encryption();
		$action = $_REQUEST['action'];
		if ($action === "encrypt") {
			$toencrypt = $_REQUEST['toencrypt'];
			if (!empty($toencrypt)) {
				$encrypted = $encryption->encode($toencrypt);
				$message = "Value = " . $encrypted;
			} else {
				$message = "Nothing to do";
			}
		} else if ($action === "decrypt") {
			$todecrypt = $_REQUEST['todecrypt'];
			if (!empty($todecrypt)) {
				$decrypted = $encryption->decode($todecrypt);
				$message = "Value = " . $decrypted;
			} else {
				$message = "Nothing to do";
			}
		}
		$hash = $encryption->hash("This is a hash");
		$token = $encryption->getToken();
		?>
		<p>One Way Hash of string 'This is a hash': <?php echo $hash; ?></p>
		<p>GUID Auth Token: <?php echo $token; ?></p>
		<form id="encrypt" name="encrypt" method="post">
			To Encrypt: <input id="toencrypt" name="toencrypt"/>
			<input type="hidden" value="encrypt" id="action" name="action"/>
			<input type="Submit" value="Encrypt"/>
		</form>
		<form id="decrypt" name="decrypt" method="post">
			To Decrypt: <input id="todecrypt" name="todecrypt"/>
			<input type="hidden" value="decrypt" id="action" name="action"/>
			<input type="Submit" value="Decrypt"/>
		</form>
		<div id="result" name="result" style="border: 1px solid blue; width: 100%; padding: 5px;">
			<?php echo $message; ?>	
		</div>
    </body>
</html>
