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

if(strlen($filter->RQ("query"))>0) {
	header('Content-type: application/json');

    switch ($filter->RQ("mode")) {
      case 'kredinor':
        $cities = $PN->KredinorCities($filter->RQ("query"));

        if($cities && count($cities)>0) {
          foreach ($cities as $city) {
            $result['options'][] = $city->Location;
          }
          echo json_encode($result);
          die;
        } else {
          echo json_encode(array("options" => array()));
          die;
        }
        
        break;
      
      default:
        $cities = $PN->Cities($filter->RQ("query"));

        if($cities && count($cities)>0) {
          foreach ($cities as $city) {
            $result['options'][] = $city->Name;
          }
          echo json_encode($result);
          die;
        } else {
          echo json_encode(array("options" => array()));
          die;
        }
        break;
    }

    

    
	

	
}
?>