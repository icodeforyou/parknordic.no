<?php
include('../_config/cfg__global.inc');
if(file_exists('../_config/cfg__server.inc')) {
  include('../_config/cfg__server.inc');
}
function __autoload($class_name) {

  try {
    $class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

    if(file_exists('../_classes/'.$class_name . '.inc')) {
      require_once('../_classes/'.$class_name . '.inc');    
    } else {
      throw new Exception("Unable to load $class_name.");
    }
  } catch (Exception $e) {
    echo $e->getMessage();
    echo "\nFile: ".$e->getFile() ." Row: ".$e->getLine();
    var_dump($e->getTrace());
    die();
  }
  
}

$db = new DB();
$PN = new ParkNordic();
$filter = new InputFilter();

header('Content-Type: application/json;charset=utf-8');

preg_match("/^(https:\/\/|http:\/\/)?([^\/]+)/i", $_SERVER['HTTP_REFERER'], $matches);
$referer = $matches[2];

if($referer == $_SERVER['HTTP_HOST']) { // OK, the call comes from the server, lets allow the request

	if($filter->RF("ajax-action") == 'check-city' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
		
		$cityname = stripos($filter->RF("cityname"), ",") !== false ? substr($filter->RF("cityname"), 0, strpos($filter->RF("cityname"), ",")) : $filter->RF("cityname");

		$exists = $db->fetch_row("SELECT CityID FROM Parking_Cities WHERE Name = :Name", array(":Name" => $cityname));
		
	
		if($exists && $db->num_rows() > 0) {
			echo json_encode(
					array(
						"ok" => "1",
						"cityid" => $exists->CityID
					)
				 );
			die;
		} else {
			echo json_encode(
					array(
						"ok" => "0",
						"city" => $cityname
					)
				 );
			die;
		}

	}

	
	if($filter->RF("ajax-action") == 'load-lot' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

		$lotid = $filter->RF("lotid");

		$lot = $PN->getLot($lotid);

		if($lot) {
			echo json_encode(
					array(
						"ok" => "1",
						"lot" => $lot
					)
				 );
			die;
		} else {
			echo json_encode(
					array(
						"ok" => "0"
					)
				 );
			die;
		}
	}

	if($filter->RF("ajax-action") == 'load-kredinor-lot' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

		$lotid = $filter->RF("lotid");

		$lot = $PN->getKredinorLot($lotid);

		if($lot) {

			$files = $PN->getKredinorFiles($lotid);

			echo json_encode(
					array(
						"ok" => "1",
						"lot" => $lot,
						"files" => $files
					)
				 );
			die;
		} else {
			echo json_encode(
					array(
						"ok" => "0"
					)
				 );
			die;
		}
	}

	if($filter->RF("ajax-action") == 'del-file' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

		$lotid = $filter->RF("lotid");
		$fileid = $filter->RF("fileid");

		$filename = $PN->getFileName($fileid);

		if($filename && strlen($filename)> 0) {

			$db->do_transaction();

			$res = $db->update("UPDATE Document SET Removed = 1 WHERE FileID = :FileID AND KredinorID = :KredinorID", array(":FileID" => $fileid, ":KredinorID" => $lotid));
			
			if($res && $db->num_rows() == 1) {

				if(file_exists(INCLUDEPATH . "/media/$lotid/$filename")) {

					if(unlink(INCLUDEPATH . "/media/$lotid/$filename") === true) {

						if(($files = @scandir(INCLUDEPATH . "/media/$lotid/$filename") && (count($files) == 2))) {
							unlink(INCLUDEPATH . "/media/$lotid/");
						}

						$db->do_commit();

						echo json_encode(
								array(
									"ok" => "1"
								)
							 );
						die;
					} else {

						$db->do_rollback();

						echo json_encode(
								array(
									"ok" => "0"
								)
							 );
						die;
					}


					
				} else {

					$db->do_rollback();

					echo json_encode(
							array(
								"ok" => "0"
							)
						 );
					die;
				}

			} else {
				
				$db->do_rollback();

				echo json_encode(
						array(
							"ok" => "0"
						)
					 );
				die;
			}
		} else {

			$db->do_rollback();

			echo json_encode(
					array(
						"ok" => "0"
					)
				 );
			die;
		}
		
	}

	if($filter->RF("ajax-action") == 'edit-lot' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {

		$address = $filter->RF("address");
		$lng = $filter->RF("lng");
		$lat = $filter->RF("lat");
		$description = $filter->RF("description");
		$lotid = $filter->RF("lotid");
		$city = $filter->RF("city");
		$longtime = $filter->RF("longtime") == "1" ? true : false;
		$newcity = (bool)$filter->RF("newcity");

		if($newcity === true) {
			$cityid = $db->insert("INSERT INTO Parking_Cities(Name, Slug) VALUES(:Name, :Slug)", array(":Name" => $city, ":Slug" => $PN->slugify($city)));
		} else {
			$cityid = $city;
		}

		// Splitta adressen på komma tecken om det finns
		if(stripos($address, ",") !== false) {
			$addresses = explode(",", $address);
			$address = $addresses[0];
		}

		$res = $db->update("UPDATE Parking_Lots SET CityID = :CityID, Address = :Address, Lat = :Lat, Lng = :Lng, Description = :Description, SupportLongtime = :SupportLongtime WHERE ID = :ID", array(":CityID" => $cityid, ":Address" => $address, ":Lat" => $lat, ":Lng" => $lng, ":Description" => $description, ":SupportLongtime" => $longtime, ":ID" => $lotid));
		if($res && $db->num_rows() == 1) {
			echo json_encode(
					array(
						"ok" => "1"
					)
				 );
			die;
		} else {
			echo json_encode(
					array(
						"ok" => "0"
					)
				 );
			die;
		}
	}

	if($filter->RF("ajax-action") == 'save-lot' && isset($_SERVER['HTTP_X_REQUESTED_WITH'])) {
	/*
		"city" : city,
		"address" : address,
		"lng" : lng,
		"lat" : lat,
		"description" : description,
		"newcity" : newcity 
	*/
		$address = $filter->RF("address");
		$lng = $filter->RF("lng");
		$lat = $filter->RF("lat");
		$description = $filter->RF("description");
		$newcity = (bool)$filter->RF("newcity");
		$city = $filter->RF("city");
		$longtime = $filter->RF("longtime") == "1" ? true : false;

		if($newcity === true) {
			$cityid = $db->insert("INSERT INTO Parking_Cities(Name, Slug) VALUES(:Name, :Slug)", array(":Name" => $city, ":Slug" => $PN->slugify($city)));
		} else {
			$cityid = $city;
		}

		// Splitta adressen på komma tecken om det finns
		if(stripos($address, ",") !== false) {
			$addresses = explode(",", $address);
			$address = $addresses[0];
		}
		

		$res = $db->insert("INSERT INTO Parking_Lots(CityID, Address, Lat, Lng, Description, SupportLongtime) VALUES(:CityID, :Address, :Lat, :Lng, :Description, :SupportLongtime)", array(":CityID" => $cityid, ":Address" => $address, ":Lat" => $lat, ":Lng" => $lng, ":Description" => $description, ":SupportLongtime" => $longtime));
		if($res && $db->num_rows() == 1) {
			echo json_encode(
					array(
						"ok" => "1"
					)
				 );
			die;
		}	
	}
}