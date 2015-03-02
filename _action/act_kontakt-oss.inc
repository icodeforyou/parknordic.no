<?php 
if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "send_contact") {

	$navn    = $filter->RF("navn");
	$bedrift = $filter->RF("bedrift");
	$epost   = $filter->RF("epost");
	$telefon = $filter->RF("telefon");
	$emne    = $filter->RF("emne");
	$beskjed = $filter->RF("beskjed");

	$mailContent = "<b>Navn</b>: $navn<br>";
	$mailContent .= "<b>Bedrift</b>: $bedrift<br>";
	$mailContent .= "<b>Epost</b>: $epost<br>";
	$mailContent .= "<b>Telefon</b>: $telefon<br>";
	$mailContent .= "<b>Emne</b>: $emne<br>";
	$mailContent .= "<b>Beskjed</b>: $beskjed<br>";

	// To send HTML mail, the Content-type header must be set
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	// Additional headers
	$headers .= 'From: '.$epost .' <'.$epost.'>' . "\r\n";
	$headers .= 'Reply-To: ' .$epost . "\r\n";
	
	// Send the actual mail
	// 
	
	if($res = (mail(CONTACT_RECIEVER, "Contact form webpage:", $mailContent, $headers)) !== false) {
		$response = 'Takk for din henvendelse. Vi kommer snart tilbake til dem.';
	}


}
?>