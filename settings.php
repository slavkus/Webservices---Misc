<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
 
require_once './config/database.php';
require_once './objects/settings_object.php';
require_once 'resource.php';

$ok=false;
$bundles_arr = array();

//Delete
if(
	isset($_POST['delete']) && !empty($_POST['delete']) && $_POST['delete'] == 'true')
	{
		if(
		(isset($_POST['sport']) && !empty($_POST['sport'])) && (isset($_POST['user']) && !empty($_POST['user'])))
		{
			$ok=true;
			$database = new Database();
			$db = $database->getConnection();

			$deleteSettings = new Settings($db);
			$deleteSettings->sport=$_POST['sport'];
			$deleteSettings->user=$_POST['user'];
			$stmtDelete = $deleteSettings->deleteSettings();
			reply(100, $bundles_arr);
		}
	}
	
else if(
	isset($_POST['update']) && !empty($_POST['update']) && $_POST['update'] == 'true')
	{
		if(
		(isset($_POST['sport']) && !empty($_POST['sport'])) && (isset($_POST['user']) && !empty($_POST['user'])))
		{
			$ok=true;
			$database = new Database();
			$db = $database->getConnection();

			$updateSettings = new Settings($db);
			$updateSettings->sport=$_POST['sport'];
			$stmtDelete = $updateSettings->updateSettings();
			reply(100, $bundles_arr);
		}
	}
	
//insert
else
{
	if	((isset($_POST['user']) && !empty($_POST['user'])) &&
		(isset($_POST['sport']) && !empty($_POST['sport'])))
		{
			$ok=true;
			$database = new Database();
			$db = $database->getConnection();

			$insertSettings = new Settings($db);
			$insertSettings->sport = $_POST['sport'];
			$insertSettings->user = $_POST['user'];

			$stmtInsert = $insertSettings->insertSettings();	
			reply(100, $bundles_arr);
	}
	else
	{
		reply(102, $bundles_arr);
	}
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
    
    $return = json_encode($response);
    if ($logging) write_log("Service result: " . $return);
 
	echo $return;
    die();
}

?>