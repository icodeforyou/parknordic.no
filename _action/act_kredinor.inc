<?php

if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "kredinor-inlogg") {
	if(strtolower($filter->RF("Brukernavn")) == "kredinor" && strtolower($filter->RF("Passord")) == "kredinor123") {

		if(isset($_POST['rememeber-me']) && $filter->RF("rememeber-me") == "1") {
			setcookie("KredinorCookie", md5("iCode4uOQ.3f88>}S8UW6S]iiCode4u"), time()+60*60*24*365);
		}

		$_SESSION['authed_kredinor'] = true;
		header("Location: /kredinor/");
		die;
	}
}
?>