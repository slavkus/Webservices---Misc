<?php
require_once('misc.php');
require_once('baza.php');

if (isset($_GET['bundle_id']) && !empty($_GET['bundle_id']) && isset($_GET['language_id']) && !empty($_GET['language_id']))
{
	$db = new Baza();
	$db->spojiDB();
			
	$bundle_id = $db->real_escape_string($_GET['bundle_id']);
	$language_id = $db->real_escape_string($_GET['language_id']);
	$bought = false;
	$userID = null;
	if (isset($_GET['token'])){
		if (VerifyToken($_GET['token']) == true)
		{
			$userID = ReturnUserId($_GET['token']);
		}
		else{
			Error(401,'Unauthorized, token mismatch');
			exit();
		}
	}
	
	//get bundle informations
	$stmt = $db->veza->prepare("select bi.bundle_id, bi.name, bi.long_description, bi.short_description,
	 mr.path as profile_picture, p.value as price, p.description as price_description, p.promo
	from bundle_info bi, bundle b, media_resource mr, price p
	where b.id = ? 
	and bi.language_id = ?
	and b.id = bi.bundle_id 
	and b.profile_picture = mr.id 
	and b.deleted_at is null
	and bi.deleted_at is null
	and mr.deleted_at is null
	and p.id = bi.price_id
	and p.start_datetime <= NOW() 
	and (p.end_datetime >= NOW() or p.end_datetime is null)
	and p.deleted_at is null");
	$stmt->bind_param("ss", $bundle_id, $language_id);
	$result = $db->queryDB($stmt);
	
	if ($result != null)
	{
		//find categories
		$stmt = $db->veza->prepare("select DISTINCT ci.category_id,ci.name
		from bundle_audio ba, audio a, audio_categories ac, category c, category_info ci 
		WHERE ba.bundle_id = ?  
		and ba.audio_id = a.id 
		and a.id = ac.audio_id 
		and ac.category_id = c.id 
		and c.id = ci.category_id 
		and ci.language_id = ba.bundle_id");
		$stmt->bind_param("s", $bundle_id);
		$resCategories = $db->queryDB($stmt);

		//find languages
		$stmt = $db->veza->prepare("SELECT l.name
		FROM language l WHERE l.id IN (SELECT bi.language_id FROM bundle_info bi WHERE bi.bundle_id = ?)");
		$stmt->bind_param("s", $bundle_id);
		$languages = $db->queryDB($stmt);
		$result[0]["languages"] = $languages;

		//find countries
		$stmt = $db->veza->prepare("SELECT ci.name
		FROM country_info ci WHERE ci.country_id IN (SELECT bc.country_id FROM bundle_countries bc WHERE bc.bundle_id = ?)
		AND ci.language_id = ?");
		$stmt->bind_param("ss", $bundle_id, $language_id);
		$countries = $db->queryDB($stmt);
		$result[0]["countries"] = $countries;
		
		//audio count
		$stmt = $db->veza->prepare("SELECT count(*) AS audio_number
		FROM bundle_audio WHERE bundle_id = ?");
		$stmt->bind_param("s", $bundle_id);
		$resAudioCount = $db->queryDB($stmt);
		$resAudioCount = $resAudioCount[0]['audio_number'];

		if ($userID!=null){
			//check if user bought bundle
			$stmt = $db->veza->prepare("select 1
			from purchased p
			where p.bundle_id = ?
			and p.user_id = ?
			and p.deleted_at is null");
			$stmt->bind_param("ss", $bundle_id,$userID);
			$resBought = $db->queryDB($stmt);
			
			if ($resBought != null)
			{
				$bought = true;
			}
		}
		
		//get every picture
		$stmt = $db->veza->prepare("select m.id,m.path, m.name
		from bundle b, media_resource m, resource_type t 
		where b.id = ? 
		and m.bundle_id = b.id 
		and m.resource_type_id = t.id 
		and t.id = 1 
		and b.deleted_at is null
		and m.deleted_at is null
		and t.deleted_at is null"); //1 for pictures
		$stmt->bind_param("s", $bundle_id);
		$resPictures = $db->queryDB($stmt);	

		$result[0]["pictures"] = $resPictures;
		
		//get every video
		$stmt = $db->veza->prepare("select m.id,m.path, m.name 
		from bundle b, media_resource m, resource_type t
		where b.id = ? 
		and m.bundle_id = b.id
		and m.resource_type_id = t.id
		and t.id = 2
		and b.deleted_at is null
		and m.deleted_at is null
		and t.deleted_at is null"); //2 for video
		$stmt->bind_param("s", $bundle_id);
		$resVideo = $db->queryDB($stmt);	

		$result[0]["video"] = $resVideo;
	


		//get all POIs
		$stmt = $db->veza->prepare("SELECT DISTINCT p.id, pi.name, pi.short_description,
		(select path from media_resource where poi_id = p.profile_picture) as profile_picture,
		(select count(*) from audio a where a.poi_id=p.id) as audio_count,
		(select sum(a.seconds) from audio a where a.poi_id=p.id) as audio_time
		from bundle b, audio a, bundle_audio ba, poi p, poi_info pi
		WHERE b.id = ?
		and ba.bundle_id = b.id
		and ba.audio_id = a.id
		and a.poi_id = p.id
		and p.id = pi.poi_id
		and pi.language_id = ?
		and b.deleted_at is null
		and ba.deleted_at is null
		and a.deleted_at is null
		and p.deleted_at is null
		and pi.deleted_at is null"); 
		$stmt->bind_param("ss", $bundle_id, $language_id);
		$resPoi = $db->queryDB($stmt);
		$result[0]["poi"] = $resPoi;
		
		if ($bought == true)
		{
			if ($bought == true)
			{
				//get all audio files for every POI
				for ($i = 0; $i < count($result[0]["poi"]); $i++){
					$stmt = $db->veza->prepare("SELECT a.path
					from poi p, audio a
					where p.id = a.poi_id
					and p.id = ?
					and p.deleted_at is null
					and a.deleted_at is null"); 
					
					$stmt->bind_param("s", $result[0]["poi"][$i]["id"]);
					$resPoiAudio = $db->queryDB($stmt);
					
					$result[0]["poi"][$i]["audio"] = $resPoiAudio;
				}
			}
		}
		
		$status = 200;
		$timestamp = GetTime();
		$result[0]["bought"] = $bought;
		$result[0]["categories"] = $resCategories;
		$result[0]["audio_number"] = $resAudioCount;
		header('Content-type: application/json');
		$json = json_encode(array("status"=>$status, "timestamp"=>$timestamp, "data"=>$result));
		echo $json;	
	}
	else
	{
		Error(404, "Bundle not found");
	}	
	$db->zatvoriDB();
}
else
{
	Error(400,"Missing or wrong parametars");
}
?>
