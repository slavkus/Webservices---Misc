<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/chat.php';
require_once 'resource.php';

$bundles_arr = array();

if (isset($_GET['user']) && !empty($_GET['user'])
	&& isset($_GET['event']) && !empty($_GET['event'])
	&& isset($_GET['approved']) && !empty($_GET['approved']))
{
	$database = new Database();
	$db = $database->getConnection();
	 
	$chat = new Chat($db);
	
	$chat->user = $_GET['user'];
	$chat->event = $_GET['event'];
	$chat->approved = $_GET['approved'];

	$stmtAll = $chat->getChat();
	$bundle_user_event = array();
	
		while ($row = $stmtAll->fetch(PDO::FETCH_ASSOC))
        {
			extract($row);
			$bundle_item = array (
				"message" => $message
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