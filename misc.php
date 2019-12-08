<?php

require_once('vendor/autoload.php');
require_once('vendor/firebase/phpjwt/src/JWT.php');
require_once('config/database.php');
require_once('objects/user.php');
require_once 'resource.php';
use \Firebase\JWT\JWT;

//petlja vraca elemente polja u obliku: elem1,elem2,emem3,...,elemN (bez zareza na kraju)
function ArrayToString($array)
{
	$string = "";
	
	require_once('baza.php');
	$db = new Baza();
	$db->spojiDB();
	
	for($i = 0; $i < count($array); $i++)
	{
		$element = $db->real_escape_string($array[$i]);
		if (count($array)-1 == $i)
		{
			$string .= "'" . $element . "'";
		}
		else
		{
			$string .= "'" . $element . "',";
		}
	}	
	return $string;
}

function Error($errNumber, $message = "")
{
	//errNumber moze biti 400, 404, 409, 500, 501
	header('Content-type: application/json');
	http_response_code($errNumber);
	echo json_encode(array("status"=>$errNumber,"message"=>$message));
}


function VerifyToken($token)
{
	//Getting the helper file for communicating with database and creating new variable for db communication
    $database = new Database();
    $db = $database->getConnection();
	
	$token = htmlspecialchars(strip_tags($token));
	
	$config = (include 'config/config.php');
	$secretKey = $config['jwt']['key'];
	$algorithm = $config['jwt']['algorithm'];

	try{
		$jwt = JWT::decode($token, $secretKey, array($algorithm));
	}
	catch(Exception $e){
		return false;
	}
	$jwt = json_decode($jwt);

	$user = new User($db);
	$user->email = $jwt->mail;
	$result = $user->getIdUsingEmail();
	
	if($result->rowCount() > 0 && $jwt->iss == 'cortex.foi.hr' && ($jwt->exp > time()))
		return true;
	else
		return false;
}

function ReturnUserId($token)
{
    $database = new Database();
    $db = $database->getConnection();
	
	$config = (include 'config/config.php');
	$secretKey = $config['jwt']['key'];
	$algorithm = $config['jwt']['algorithm'];
	
	try{
		$jwt = JWT::decode($token, $secretKey, array($algorithm));
	}
	catch(Exception $e){
		return null;
	}
	$jwt = json_decode($jwt);

    $user = new User($db);
    $user->email = $jwt->mail;
    $result = $user->getIdUsingEmail();
	
	if($result->rowCount() > 0 && $jwt->iss == 'cortex.foi.hr' && ($jwt->exp > time()))
	{
        while ($row = $result->fetch(PDO::FETCH_ASSOC))
        {
            extract($row);

            $userId = $id;
        }
        return $userId;
    }
	else
		return null;
}

function GetTime()
{
	return date('Y-m-d H:i:s');
}