<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/home_object.php';
require_once 'resource.php';

if (isset($_GET['user']) && !empty($_GET['user']))
	/*
	&& isset($_GET['sport']) && !empty($_GET['sport'])
	&& isset($_GET['id']) && !empty($_GET['id'])
	&& isset($_GET['src_radius']) && !empty($_GET['src_radius'])
	&& isset($_GET['curr_pos_lat']) && !empty($_GET['curr_pos_lat'])
	&& isset($_GET['curr_pos_lon']) && !empty($_GET['curr_pos_lon'])
	*/
{
	$database = new Database();
	$db = $database->getConnection();
	 
	$home = new Home($db);
	
	$home->user = $_GET['user'];
	
	//$home->sport = $_GET['sport'];
	//$home->id = $_GET['id'];
	/*
	$home->src_radius = $_GET['src_radius'];
	$home->curr_pos_lat = $_GET['curr_pos_lat'];
	$home->curr_pos_lon = $_GET['curr_pos_lon'];
	*/

	$stmtSports = $home->getUserInterest();
	$bundle_user_Sports = array();
	
		while ($rowSports = $stmtSports->fetch(PDO::FETCH_ASSOC))
        {
			extract($rowSports);
			$bundle_item_Sports = array (
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
				"organisator" => $organisator
			);
            array_push($bundle_user_Sports, $bundle_item_Sports);
		}
		
		reply(100, $bundle_user_Sports);

	}
	 
	else{
        reply(102, $bundle_user_Sports);
	}
	
//varazdin
$testLong = 16.33778;
$testLat = 46.30444;
	
// earthRadius varijabla je u kilometrima

function haversineGreatCircleDistance(
  $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371)
{
  // convert from degrees to radians
  $latFrom = deg2rad($latitudeFrom);
  $lonFrom = deg2rad($longitudeFrom);
  $latTo = deg2rad($latitudeTo);
  $lonTo = deg2rad($longitudeTo);

  $latDelta = $latTo - $latFrom;
  $lonDelta = $lonTo - $lonFrom;

  $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
    cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
  return $angle * $earthRadius;
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