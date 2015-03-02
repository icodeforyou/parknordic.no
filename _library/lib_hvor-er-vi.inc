<?php
if($nav->getArgs(0) !== false) {
	
	$lots = $PN->getCityLots($nav->getArgs(0));

	if($lots) {
		$geopos = new stdClass();
		$geopos->Lat = $lots[0]->Lat;
		$geopos->Lng = $lots[0]->Lng;
	}

} else {
	$lots = $PN->getCityLots();
}
?>