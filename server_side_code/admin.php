<?php
require("config.inc.php");

	$query = "SELECT * FROM users";
	
	$stmt = $db->query($query);

if (!empty($_POST)) {

	$response = array("error" => FALSE);
	
	function send_gcm_notify($reg_id, $message) {
	
		define("GOOGLE_API_KEY", "AIzaSyBsGSPuDKtN5KNmxK1zSqonaMMHUmAfeFQ");
		define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");
	
        $fields = array(
            
			'to'  						=> $reg_id ,
			'priority'					=> "high",
            'notification'              => array( "title"=>"Android Learning", "body" => $message ),
        );

		echo "<br>";
		echo json_encode($fields);
		echo "<br>";
		
        $headers = array(
            'Authorization: key=' . GOOGLE_API_KEY,
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
		
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Problem occurred: ' . curl_error($ch));
        }
		
        curl_close($ch);
        echo $result;
    }
	
    $reg_id = $_POST['fcm_id'];
    $msg = $_POST['msg'];
	
    send_gcm_notify($reg_id, $msg);
	
}
?>

<!Doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<!--Import Google Icon Font-->
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Import materialize.css-->
		<link type="text/css" rel="stylesheet" href="../css/materialize.min.css"  media="screen,projection"/>
	
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
		
		<title>Admin | Circle Eduventures</title>
		
		<style>body, .row{ text-align: center;}</style>
		
		<script>
		$(function(){
			$("textarea").val("");
		});
		function checkTextAreaLen(){
			var msgLength = $.trim($("textarea").val()).length;
			if(msgLength == 0){
				alert("Please enter message before hitting submit button");
				return false;
			}else{
				return true;
			}
		}
		</script>
		
	</head>
	
	<body>
	
		<h1>Admin Panel</h1>
		<div class="row">
		<div class="col s12 m4 l2"><p></p></div>
			<form class="col s12 m4 l8" action="admin.php" method="post" onsubmit="return checkTextAreaLen()">
				<div class="row">
					<div class="input-field col s12">
						<p>Select user</p><br><br>
						<select name="fcm_id" required>
							<option value="" disabled selected>Select User</option>
							<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								echo "<option value='".$row['fcm_registered_id']."'>".$row['name']." &lt;".$row['email']."&gt;</option>";
							} ?>
						</select><br><br>
						<textarea id="msg" name="msg" class="materialize-textarea" placeholder="Type your message"></textarea>
						<br><br>
		
						<button class="btn waves-effect waves-light" type="submit" name="action">Send</button>
					</div>
				</div>
			</form>
			<div class="col s12 m4 l2"><p></p></div>
		</div>
	
		<!--Import jQuery before materialize.js-->
		<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		<script type="text/javascript" src="../js/materialize.min.js"></script>
		
    </body>
</html>