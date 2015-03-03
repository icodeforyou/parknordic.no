<?php
if($nav->getArgs(0) !== false) {
	
	$lots = $PN->getCityLots($nav->getArgs(0));

	if($lots) {
		$geopos = new stdClass();
		$geopos->Lat = $lots[0]->Lat;
		$geopos->Lng = $lots[0]->Lng;
	}

} else {

	if(strlen($filter->RF("q"))>0) {

		try {
			$lots = $PN->getZipLots($filter->RF("lat"), $filter->RF("lng"));

				$geopos = new stdClass();
				$geopos->Lat = $filter->RF("lat");
				$geopos->Lng = $filter->RF("lng");
				$placemarker = true;


		} catch (Exception $e) {
			$error = $e->getMessage();
		}


	} else {
		$lots = $PN->getCityLots();
	}

}
?>