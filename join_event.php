<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/user_by_event.php';
require_once 'resource.php';

$ok=false;
$bundles_arr = array();

//Delete
if(isset($_POST['delete']) && !empty($_POST['delete']) &&
	$_POST['delete'] == 'true'){
	if ((isset($_POST['user']) && !empty($_POST['user'])) &&
	(isset($_POST['event']) && !empty($_POST['event'])))
	{
		$ok=true;
		$database = new Database();
		$db = $database->getConnection();

		$deleteUserInEvent = new User_by_event($db);
		$deleteUserInEvent->user=$_POST['user'];
		$deleteUserInEvent->event=$_POST['event'];
		$stmtDelete = $deleteUserInEvent->deleteUserInEvent();
		reply(100, $bundles_arr);
	}
}
else
{
	if	((isset($_POST['user']) && !empty($_POST['user'])) &&
		(isset($_POST['event']) && !empty($_POST['event'])) &&
		(isset($_POST['score']) && !empty($_POST['score'])) &&
		(isset($_POST['approved']) && !empty($_POST['approved'])))
	{
		//(isset($_POST['decision_timestamp']) && !empty($_POST['decision_timestamp'])
		//(isset($_POST['application_timestamp']) && !empty($_POST['application_timestamp'])) &&

		//insert{
			$ok=true;
			$database = new Database();
			$db = $database->getConnection();

			$insertUserInEvent = new User_by_event($db);
			$insertUserInEvent->user = $_POST['user'];
			$insertUserInEvent->event = $_POST['event'];
			$insertUserInEvent->score = $_POST['score'];
			$insertUserInEvent->approved = $_POST['approved'];
			//$insertUserInEvent->decision_timestamp = $_POST['decision_timestamp'];
			//$insertUserInEvent->application_timestamp = $_POST['application_timestamp'];


			$stmtInsert = $insertUserInEvent->insertUserInEvent();	
			reply(100, $bundles_arr);
	}
	else
	{
		reply(102, $bundles_arr);
	}
}
/*
if ((isset($_GET['id']) && !empty($_GET['id'])) &&
	(isset($_GET['id_user']) && !empty($_GET['id_user'])))
{
	$ok=true;
	
	$database = new Database();
	$db = $database->getConnection();

	$create_event = new Create_event($db);
	
	$create_event->id_user = $_GET['id_user'];

	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		extract($row);
		$bundle_item = array (
			"id_user" => $organisator
		);
		
		array_push($bundles_arr, $bundle_item);
	}
 
	reply(100, $bundles_arr);
}

if($ok===false)
{
	reply(102, $bundles_arr);
}
*/		
function reply($msgId, $data = NULL) {
    $logging = false;
 
    if ($data === NULL) {
        $data = array("data" => NULL);
    }
    
    $return = $data;
    include_once ('ini/loadIni.php');
    
    $response = array();
    $response["responseId"] = $msgId;
    $response["responseText"] = $settings[$msgId];
	$response["timeStamp"] = time();
    
    $response["data"] = $data;
    
    $return = json_encode($response);
    if ($logging) write_log("Service result: " . $return);
 
	echo $return;
    die();
}

?>