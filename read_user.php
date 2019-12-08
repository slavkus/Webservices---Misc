<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/user.php';
require_once 'resource.php';

$bundles_arr = array();

if (isset($_GET['id']) && !empty($_GET['id']))
{
	$database = new Database();
	$db = $database->getConnection();
	 
	// initialize object
	$user = new User($db);
	
	$user->id = $_GET['id'];
	
	// query products
	
	$stmt = $user->getUser();
	 
	// check if more than 0 record found
	
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);
		$bundle_item = array (
			"id" => $id,
			"username" => $username,
			"email" => $email,
			"first_name" => $first_name,
			"last_name" => $last_name,
			"date_of_birth" => $date_of_birth,
		);
		
		array_push($bundles_arr, $bundle_item);
	}
 
	reply(100, $bundles_arr);
}
 
else{
	reply(102, $bundles_arr);
	/*http_response_code(404);
	echo json_encode(
		array("error" => "No bundles found.")
	);*/
}
	
	/**
 * This method uniquely replies to all requests. It always returns the responseId and responseText.
 * responseId should contains the response number enumerated in ini.php.
 * responseText also contains the repsonse text enumerated in ini.php.
 * Finally, the reply contains any other data and alltogether is formated in Json.
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

/*else
{
    http_response_code(400);
    echo json_encode(
        array("error" => "Wrong or missing parameters.")
    );
}*/
?>