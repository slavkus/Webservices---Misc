<?php
	//Dohvacanje potrebnih stvari
	require_once('misc.php');
	require_once('vendor/autoload.php');
	require_once('vendor/firebase/phpjwt/src/JWT.php');
	use \Firebase\JWT\JWT;
    require_once 'config/database.php';
    require_once 'objects/user.php';
    require_once 'resource.php';
	$config = (include 'config/config.php');

	if(isset($_POST['email']) && isset($_POST['username']))
	{
        $database = new Database();
        $db = $database->getConnection();

        $user = new User($db);
        $user->email = $_POST['email'];
        $user->username = $_POST['username'];

		/*
        $result = $user->getIdUsingEmail();

		if($result->rowCount() == 0){
			if(!($user->insert()))
            {
                http_response_code(500);
                echo json_encode(
                    array("error" => "Internal error, adding user problem")
                );
                exit();
            }
		}
		else
        {
            while ($row = $result->fetch(PDO::FETCH_ASSOC))
            {
                extract($row);

                $deletedAt = $deleted_at;
            }
            if(!is_null($deletedAt))
            {
                if(!($user->update()))
                {
                    http_response_code(500);
                    echo json_encode(
                        array("error" => "Internal error, updating user problem")
                    );
                    exit();
                }
            }
        }
		*/
        if(function_exists('random_bytes'))
        {
            try {
                $tokenId = base64_encode(random_bytes(32));
            } catch (Exception $e) {
                http_response_code(500);
                echo json_encode(
                    array("error" => "Internal error, token issue problem")
                );
                exit();
            }
        }
        if (function_exists('mcrypt_create_iv')) {
            $tokenId = base64_encode(mcrypt_create_iv(32, MCRYPT_DEV_URANDOM));
        }
        //random token id
		$issuedAt   = time();
		$notBefore  = $issuedAt+1;
		$expire     = $notBefore + 31104000;            // 1 year
		$serverName = $config["serverName"];
		
		$data = [
			'iat'  => $issuedAt,         // Issued at: time when the token was generated
			'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
			'iss'  => $serverName,       // Issuer
			'nbf'  => $notBefore,        // Not before
			'exp'  => $expire,           // Expire
			'mail' => $user->email	     // User email
		];
		$data = json_encode($data);
		header('Content-type: application/json');
		$secretKey = $config['jwt']['key'];
		$algorithm = $config['jwt']['algorithm'];
		
		try
        {
			$jwt = JWT::encode($data, $secretKey, $algorithm);
		}
		catch(Exception $e)
        {
            http_response_code(500);
            echo json_encode(
                array("error" => "Internal error, token issue problem")
            );
            exit();
		}
		$data = [
			'access_token'=> $jwt,
			'expires_in'=> $expire,
			'issued'=> date("d M Y H:i:s", $issuedAt),
			'expires'=> date("d M Y H:i:s", $expire)
		];
        http_response_code(200);
		$data = json_encode(array("data"=>$data));
		echo $data;
	}
	else
    {
        http_response_code(400);
        echo json_encode(
            array("error" => "Missing parameters")
        );
	}