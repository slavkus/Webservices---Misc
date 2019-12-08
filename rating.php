<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/user_by_event.php';
require_once 'resource.php';

if (isset($_GET['user']) && !empty($_GET['user']))
{
	$database = new Database();
	$db = $database->getConnection();
	 
	$user_by_event = new User_by_event($db);
	
	$user_by_event->user = $_GET['user'];

	$stmtAll = $user_by_event->getRatings();
	$bundle_user_event = array();
	
	$rowAll = $stmtAll->fetch(PDO::FETCH_ASSOC);
	array_push($bundle_user_event, $rowAll);
		/*while ($rowAll = $stmtAll->fetch(PDO::FETCH_ASSOC))
        {
			extract($rowAll);
			$bundle_item_All = array (
				"AVG(score)" => $score,
			);
			
            array_push($bundle_user_event, $bundle_item_All);
		}*/
		
		reply(100, $bundle_user_event);

	}
	 
	else{
        reply(102, $bundle_user_event);
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