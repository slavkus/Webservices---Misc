<?php

// include our OAuth2 Server object
require_once __DIR__.'/server.php';


if(!isset(getallheaders()["Authorization"])){
	http_response_code(401);
	echo json_encode(
		array("error" => "Unauthorized request. Bearer token missing in header.")
	);
	exit();
}

$authorizationArray = explode(" ", getallheaders()["Authorization"]);
if(count($authorizationArray) !=2 || $authorizationArray[0] != "Bearer"){
	http_response_code(402);
	echo json_encode(
		array("error" => "Unauthorized request. Bearer token incorrect format.")
	);
	exit();
}

// Handle a request to a resource and authenticate the access token
if (!$server->verifyResourceRequest(OAuth2\Request::createFromGlobals())) {
    $server->getResponse()->send();
    die;
}
//echo json_encode(array('success' => true, 'message' => 'You accessed my APIs!'));

//all ok - script can continue
?>