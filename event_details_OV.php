<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/event.php';
require_once 'resource.php';

if (isset($_GET['id']) && !empty($_GET['id']))
{
	$database = new Database();
	$db = $database->getConnection();
	 
	$event = new Event($db);
	
	$event->id = $_GET['id'];

	$stmt = $event->getEvent();
	$bundles_arr = array(); 

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
			extract($row);
			$bundle_item = array (
				"id" => $id,
				"name" => $name,
				"date" => $date,
				"time" => $time,
				"longitude" => $longitude,
				"latitude" => $latitude,
				"minimum_players" => $minimum_players,
				"maximum_players" => $maximum_players,
				"description" => $description,
				"address" => $address,
				"sport" => $sport,
				"organisator" => $organisator,
			);
			
            array_push($bundles_arr, $bundle_item);
		}
	 
		reply(100, $bundles_arr);

	}
	 
	else{
        reply(102, $bundles_arr);
	}
	
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
    
    /* Important !
     * It's important to cast data as an array because
     * if data is null and we merge them in array,
     * output array will result in null also.
    */
    
   // $response = array_merge($response, (array) $data);
    
    $return = json_encode($response);
    if ($logging) write_log("Service result: " . $return);
 
	echo $return;
		die();
}

?>