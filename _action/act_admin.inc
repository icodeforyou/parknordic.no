<?php

if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "admin-inlogg") {
	if(strtolower($filter->RF("Brukernavn")) == "admin" && strtolower($filter->RF("Passord")) == "pnadmin123") {

		if(isset($_POST['rememeber-me']) && $filter->RF("rememeber-me") == "1") {
			setcookie("ParkNordicCookie", md5("iCode4uOQ.3fD3DD2123RDSDASD:_6S]iiCode4u"), time()+60*60*24*365);
		}

		$_SESSION['authed_pn'] = true;
		header("Location: /admin/");
		die;
	}
}


if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "save-news") {

	$news = $filter->RF("news");
	$headline = $filter->RF("headline");

	if(strlen($news)>0 && strlen($headline)>0) {
		$slug = $PN->slugify($headline);

		$newsid = $db->insert("INSERT INTO News(Title, News, Date_Added, Slug) VALUES(:Title, :News, NOW(), :Slug)", array(":Title" => $headline, ":News" => $news, ":Slug" => $slug));
		header("Location: /admin/?newsid=$newsid#news");
		die;
	} else {
		$error = "Tittelen og nyheten kan ikke være tom";
	}

}


if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "edit-news") {

	$newsid = $filter->RF("-edit");
	$news = $filter->RF("news");
	$headline = $filter->RF("headline");

	if(strlen($news)>0 && strlen($headline)>0) {
		
		$slug = $PN->slugify($headline);

		$newsid = $db->update("UPDATE News SET Title = :Title, News = :News, Slug = :Slug WHERE id = :id", array(":Title" => $headline, ":News" => $news, ":Slug" => $slug, ":id" => $filter->RF("-edit")));
		header("Location: /admin/?newsid=$newsid#edit");
		die;

	} else {
		$error = "Tittelen og nyheten kan ikke være tom";
	}

}


if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "save-new-kredinor") {
	
	$kredinor_city = $filter->RF("kredinor_city");
	$address = $filter->RF("address");
	$depcode = $filter->RF("depcode");
	$type = $filter->RF("type");
	$machines = $filter->RF("machines");
	$information = $filter->RF("information");

	switch ($filter->RF("-mode")) {
		case 'edit':

			$KredinorID = $filter->RF("-edit");

			$parameters = array(
				":DepartmentCode" => $depcode,
				":Machines" => $machines,
				":Address" => $address,
				":Location" => $kredinor_city,
				":Type" => $type,
				":Information" => $information,
				":KredinorID" => $KredinorID
			);
			
			$db->do_transaction();

			try {
				$db->update("UPDATE Kredinor SET DepartmentCode = :DepartmentCode, Machines = :Machines, Address = :Address, Location = :Location, Type = :Type, Information = :Information WHERE KredinorID = :KredinorID", $parameters);
			} catch (Exception $e) {
				$_error = "Vi kunne ikke oppdatere Kredinor dokument";
			}

			if($PN->files_uploaded()) {
				foreach ($_FILES["files"]["error"] as $key => $error) {
					if ($error == 0) {
					    
					    $tmp_name = $_FILES["files"]["tmp_name"][$key];
					    $name = $_FILES["files"]["name"][$key];
					    $extension = strtolower(substr( $_FILES['files']['name'][$key], -3, 3));
					    $filename = $PN->slugify(basename($name, ".$extension")) .".$extension";

					    if(!file_exists(INCLUDEPATH . "/media/$KredinorID/")) {
					    	mkdir(INCLUDEPATH . "/media/$KredinorID/");
					    }
					 
					    if(move_uploaded_file($tmp_name, INCLUDEPATH . "/media/$KredinorID/" . $filename) === true) {
					    	$FileIDs[] = $db->insert("INSERT INTO Document(KredinorID, FileName) VALUES(:KredinorID, :FileName)", array(":KredinorID" => $KredinorID, ":FileName" => $filename));
					    }
					}
				}

				if(count($FileIDs) != count($_FILES['files']['name'])) {
					$_error = "Alle bildene kan ikke være knyttet til innlegget";
				}
			}

			if(!isset($_error)) {
				$db->do_commit();
				header("Location: /admin/?kredinorid=$KredinorID#kredinor");
				die;
			} else {
				$db->do_rollback();
			}


			break;
		
		default:
		case 'new':
			
			$parameters = array(
				":DepartmentCode" => $depcode,
				":Machines" => $machines,
				":Address" => $address,
				":Location" => $kredinor_city,
				":Type" => $type,
				":Information" => $information,
				":Slug" => $PN->slugify($address)
			);

			$KredinorID = $db->insert("INSERT INTO Kredinor(DepartmentCode, Machines, Address, Location, Type, Information, Slug) VALUES(:DepartmentCode, :Machines, :Address, :Location, :Type, :Information, :Slug)", $parameters);

			if($KredinorID && $db->num_rows() == 1) {
				if($PN->files_uploaded()) {
					foreach ($_FILES["files"]["error"] as $key => $error) {
						if ($error == 0) {
						    
						    $tmp_name = $_FILES["files"]["tmp_name"][$key];
						    $name = $_FILES["files"]["name"][$key];
						    $extension = strtolower(substr( $_FILES['files']['name'][$key], -3, 3));
						    $filename = $PN->slugify(basename($name, ".$extension")) .".$extension";

						    if(!file_exists(INCLUDEPATH . "/media/$KredinorID/")) {
						    	mkdir(INCLUDEPATH . "/media/$KredinorID/");
						    }

						    if(move_uploaded_file($tmp_name, INCLUDEPATH . "/media/$KredinorID/" . $filename) === true) {
						    	$FileIDs[] = $db->insert("INSERT INTO Document(KredinorID, FileName) VALUES(:KredinorID, :FileName)", array(":KredinorID" => $KredinorID, ":FileName" => $filename));
						    }
						}
					}
					if(count($FileIDs) == count($_FILES['files']['name'])) {
						header("Location: /admin/?kredinorid=$KredinorID#kredinor");
						die;
					}
				}
				header("Location: /admin/?kredinorid=$KredinorID#kredinor");
				die;
			} else {
				$error = "Kunde inte skapa kredinor post";
			}


			break;
	}

}
?>