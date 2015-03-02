<?php
if(isset($_SESSION['authed_kredinor']) && $_SESSION['authed_kredinor'] === true) {
	if($nav->getArgs(0) !== false) {
		$kredinor_obj = $PN->Kredinor($nav->getArgs(0));
		$documents = $PN->getKredinorDocuments($nav->getArgs(0));
	}
}
if($_COOKIE["KredinorCookie"] == "iCode4uOQ.3f88>}S8UW6S]iiCode4u") {
	$_SESSION['authed_kredinor'] = true;
}
?>