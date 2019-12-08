<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/create_event_object.php';
require_once 'resource.php';

$ok=false;
$bundles_arr = array();

if	(
	(isset($_POST['id']) && !empty($_POST['id'])) &&
	(isset($_POST['name']) && !empty($_POST['name'])) &&
	(isset($_POST['date_var']) && !empty($_POST['date_var'])) &&
	(isset($_POST['time_var']) && !empty($_POST['time_var'])) &&
	(isset($_POST['longitude']) && !empty($_POST['longitude'])) &&
	(isset($_POST['latitude']) && !empty($_POST['latitude'])) &&
	(isset($_POST['minimum_players']) && !empty($_POST['minimum_players'])) &&
	(isset($_POST['maximum_players']) && !empty($_POST['maximum_players'])) &&
	(isset($_POST['description']) && !empty($_POST['description'])) &&
	(isset($_POST['address']) && !empty($_POST['address'])) &&
	(isset($_POST['sport']) && !empty($_POST['sport']))&&
	(isset($_POST['organisator']) && !empty($_POST['organisator'])))
{

	//update
	if (isset($_POST['update']) && !empty($_POST['update']) &&
	$_POST['update'] == 'true')
	{
		$ok=true;
		$database = new Database();
		$db = $database->getConnection();

		$create_event = new Create_event($db);
		$create_event->name = $_POST['name'];
		$create_event->date_var = $_POST['date_var'];
		$create_event->time_var = $_POST['time_var'];
		$create_event->longitude = $_POST['longitude'];
		$create_event->latitude = $_POST['latitude'];
		$create_event->minimum_players = $_POST['minimum_players'];
		$create_event->maximum_players = $_POST['maximum_players'];
		$create_event->description = $_POST['description'];
		$create_event->address = $_POST['address'];
		$create_event->sport = $_POST['sport'];
		$create_event->organisator = $_POST['organisator'];
		
		$create_event = new Create_event($db);
		$stmtUpdate = $create_event->updateEvent();
		
		reply(100, $bundles_arr);
		
	}
	//insert
	else
	{
		$ok=true;
		$database = new Database();
		$db = $database->getConnection();

		$create_event = new Create_event($db);
		$create_event->id = $_POST['id'];
		$create_event->name = $_POST['name'];
		$create_event->date_var = $_POST['date_var'];
		$create_event->time_var = $_POST['time_var'];
		$create_event->longitude = $_POST['longitude'];
		$create_event->latitude = $_POST['latitude'];
		$create_event->minimum_players = $_POST['minimum_players'];
		$create_event->maximum_players = $_POST['maximum_players'];
		$create_event->description = $_POST['description'];
		$create_event->address = $_POST['address'];
		$create_event->sport = $_POST['sport'];
		$create_event->organisator = $_POST['organisator'];

		$stmtInsert = $create_event->insertEvent();	
		
		reply(100, $bundles_arr);

	}
	
}
else
{
	reply(102, $bundles_arr);
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