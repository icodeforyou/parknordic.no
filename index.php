<?php 
mb_internal_encoding("UTF-8");
error_reporting(E_ALL ^ E_NOTICE);
date_default_timezone_set("Europe/Oslo");

include('_config/cfg__global.inc');
if(file_exists('_config/cfg__server.inc')) {
  include('_config/cfg__server.inc');
}
function __autoload($class_name) {

  try {
    $class_name = str_replace( '_', DIRECTORY_SEPARATOR, $class_name );

    if(file_exists('_classes/'.$class_name . '.inc')) {
      require_once('_classes/'.$class_name . '.inc');    
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

session_start();

$nav = new Nav();

// include libraryscripts and actionscripts
if(($incl_url = $nav->includer("action")) !== FALSE) {
  include($incl_url);
}
if(($incl_url = $nav->includer("library")) !== FALSE) {
  include($incl_url);
}
if(isset($_GET['logout'])) {
  
  session_unset();
  session_destroy();

  header("Location: /",TRUE,302);
}

if(strlen($filter->RF("-action"))>0 && $filter->RF("-action") == "apply") {

  $Navn    = $filter->RF("Navn");
  $Postadresse = $filter->RF("Postadresse");
  $Postnr   = $filter->RF("Postnr");
  $Orgnr = $filter->RF("Orgnr");
  $Epost    = $filter->RF("Epost");
  $Telefon = $filter->RF("Telefon");
  $Sted = $filter->RF("Sted");
  $Kommentar = $filter->RF("Kommentar");
  $Betalingstype = $filter->RF("Betalingstype");

  $mailContent = "<b>Navn</b>: $Navn<br>";
  $mailContent .= "<b>Postadresse</b>: $Postadresse<br>";
  $mailContent .= "<b>Postnr</b>: $Postnr<br>";
  $mailContent .= "<b>Orgnr</b>: $Orgnr<br>";
  $mailContent .= "<b>Epost</b>: $Epost<br>";
  $mailContent .= "<b>Telefon</b>: $Telefon<br>";
  $mailContent .= "<b>Sted</b>: $Sted<br>";
  $mailContent .= "<b>Kommentar</b>: $Kommentar<br>";
  $mailContent .= "<b>Betalingstype</b>: $Betalingstype<br>";

  // To send HTML mail, the Content-type header must be set
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
  
  // Additional headers
  $headers .= 'From: '.$Epost .' <'.$Epost.'>' . "\r\n";
  $headers .= 'Reply-To: ' .$Epost . "\r\n";
  
  // Send the actual mail
  // 
  
  if($res = (mail(ORDER_RECIEVER, "Søknader om langtidsparkering er mottatt:", $mailContent, $headers)) !== false) {
    header("Location: /".$filter->RF("uri")."/?recieved");
    die;
  }


}

$news = $PN->getNews();
?><!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <title>Park Nordic</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
    <script type="text/javascript" src="/js/pn.js"></script>
    <script type="text/javascript" src="/js/jquery.validate.min.js"></script>
    <script type="text/javascript" src="/js/messages_no.js"></script>
  <!--  <script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places"></script>-->
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false&amp;libraries=places"></script>
    <script type="text/javascript" src="/js/jquery.geocomplete.js"></script>
    <script type="text/javascript" src="/js/bootstrap-fileupload.js"></script>

    <!-- Bootstrap -->
    <link href="/css/bootstrap.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="/css/master.css?<?php echo time(); ?>" media="screen">
    <link href='http://fonts.googleapis.com/css?family=Quicksand:300,400,700' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Condensed:400,700,300' rel='stylesheet' type='text/css'>
    
      
    
  </head>
  <?php if($nav->getInclude() == "hvor-er-vi") { ?>
  <body onload="initialize()">
  <?php } else { ?>
  <body>
  <?php } ?>
    <div id="wrapper">
      <div id="top-wrapper" class="row">
        <div class="container">
          <div id="top" class="span12">
            <div class="row">
              <div class="span3" id="logo">
                <a href="/"><img src="/img/park-nordic-logo.png" border="0"></a>
              </div>
              <div class="span9 pull-right">
                <?php echo $nav->getMenu(); ?>
              </div> 
            </div>
            
          </div>
          <div class="row">
            
              <div class="span8">
                <img src="/img/<?php echo $PN->Banner($nav->getInclude()); ?>" alt="">
              </div>
              <div class="span4">
                <div id="right-top-frame">
                  <h4><a href="/nyheter/">Siste nytt</a></h4>
                  <p style="padding: 5px 0; color: #111111">
                    <?php if($news !== false) { ?>
                      <?php echo $PN->substrwords($news[0]->News,190, "… <a href=\"/nyheter/".strftime("%Y/%m",strtotime($news[0]->Date_Added)) ."/" .$news[0]->Slug ."/\">Les mer</a>"); ?>
                    <?php } ?>
                  </p>
                </div>
                <div id="right-bottom-frame">
                    <h4><a href="/kontrollavgift/">Kontrollavgift?</a></h4>
                    <p style="padding: 5px 0; color: #fff">
                      Kontrollavgift? Har du mottatt kontrollavgift.<br><a href="/kontrollavgift/">Les her</a> om hvordan ta kontakt.
                    </p>
                </div>
              </div>
            
          </div>
        </div>
      </div>
      <div id="content-wrapper" class="row">
        <div class="container" id="main-content">
          <?php include($nav->includer("content")); ?>
        </div>
      </div>
      <div id="push"></div>
    </div>
    <div id="footer" class="row">
      <div class="container">
        <div class="span9" style="margin-left:0">
          <p><strong>Park Nordic AS</strong>, Postboks 6519 Etterstad, 0606 Oslo - Tlf: +47 21 42 20 00 - Fax: +47 22 19 75 00 - Epost: office@parknordic.no</p>
        </div>
        <div class="span2 pull-right">
          <img src="/img/footer-logo.jpg">
        </div>
      </div>
      
    </div>
    
    <script src="/js/bootstrap.min.js"></script>
  </body>
</html>