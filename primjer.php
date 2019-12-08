<?php
require_once('misc.php');
/* require_once('baza.php'); we don't have baza.php  */

if (isset($_GET['bundle_id']) && !empty($_GET['bundle_id']) && isset($_GET['language_id']) && !empty($_GET['language_id']))
{
	$db = new database();
	$db->spojiDB();
			
	$bundle_id = $db->real_escape_string($_GET['bundle_id']);
	$language_id = $db->real_escape_string($_GET['language_id']);
	$bought = false;
	$userID = null;
	if (isset($_GET['token'])){
		if (VerifyToken($_GET['token']) == true)
		{
			$userID = ReturnUserId($_GET['token']);
		}
		else{
			Error(401,'Unauthorized, token mismatch');
			exit();
		}
	}
	
	
	
	if ($userID!=null){
		$status = 200;
		$timestamp = GetTime();
		$result[0]["bought"] = $bought;
		$result[0]["categories"] = $resCategories;
		$result[0]["audio_number"] = $resAudioCount;
		header('Content-type: application/json');
		$json = json_encode(array("status"=>$status, "timestamp"=>$timestamp, "data"=>$result));
		echo $json;	
	}
		
		
	

}
else
{
	Error(400,"Missing or wrong parametars");
}
?>
