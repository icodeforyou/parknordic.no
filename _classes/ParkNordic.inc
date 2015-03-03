<?php
/**
* 		
*/
class ParkNordic
{
	private $db;

	function __construct()
	{
		$this->db = DB::obtain();
	}

	public function Cities($name = '')
	{
		if(strlen($name)>0) {
			return $this->db->fetch_all("SELECT Name, Slug FROM Parking_Cities WHERE Name LIKE :Name", array(":Name" => $name."%"));
		} else {
			return $this->db->fetch_all("SELECT Name, Slug FROM Parking_Cities");
		}
		
	}

	public function KredinorCities($name = '')
	{
		if(strlen($name)>0) {
			return $this->db->fetch_all("SELECT Location, Slug FROM Kredinor WHERE Location LIKE :Location GROUP BY Location", array(":Location" => $name."%"));
		} else {
			return $this->db->fetch_all("SELECT Location, Slug FROM Kredinor");
		}
	}

	public function getKredinorPDF($kredinorid)
	{
		return "N/A";
	}

	public function files_uploaded() 
	{

		// bail if there were no upload forms
		if(empty($_FILES)) {
			return false;
		}

		// check for uploaded files
		$files = $_FILES['files']['tmp_name'];
		foreach( $files as $field_title => $temp_name ){
			if( !empty($temp_name) && is_uploaded_file( $temp_name )){
				// found one!
				return true;
			}
		}   
		// return false if no files were found
	   return false;
	}

	public function slugify($text) 
	{
		// replace non letter or digits by -
		$text = preg_replace('~[^\\pL\d]+~u', '-', $text);

		// trim
		$text = trim($text, '-');

		// transliterate
		if (function_exists('iconv'))
		{
			$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		}

		// lowercase
		$text = strtolower($text);

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);

		if (empty($text))
		{
			return 'n-a';
		}

		return $text;
	}

	public function substrwords($text,$maxchar,$end='...')
	{
		if(strlen($text)>$maxchar) {
			
			$words=explode(' ',$text);
			$output = '';
			$i=0;
			
			while(1) {
				$length = (strlen($output)+strlen($words[$i]));
				if($length>$maxchar) {
					break;
				} else {
					$output = $output.' '.$words[$i];
					++$i;
				}
			}
		} else {
			$output = $text;
		}
		
		if(strlen($text)>strlen($output)) {
			return $output.$end;
		} else {
			return $output;
		}
		
	}

	public function getCityPos($cityname)
	{
		return $this->db->fetch_row("SELECT Lat, Lng FROM Parking_Cities WHERE Name = :Name", array(":Name" => $cityname));
	}

	public function loadNews($year, $month, $slug)
	{
		return $this->db->fetch_row("SELECT * FROM News WHERE Slug = :Slug AND DATE_FORMAT(Date_Added, \"%Y/%m\") = :Date_Added", array(":Slug" => $slug, ":Date_Added" => $year."/".$month));
	}

	public function getNews($newsslug='', $offset = 0, $limit = 1)
	{
		if(strlen($newsslug)>0) {
			return $this->db->fetch_row("SELECT * FROM News WHERE Slug = :Slug", array(":Slug" => $newsslug));
		} else {
			$this->db->setOffset($offset);
			$this->db->setLimit($limit);
			return $this->db->fetch_all("SELECT * FROM News ORDER BY Date_Added DESC");
		}
	}

	public function getLot($lotid)
	{
		return $this->db->fetch_row("SELECT pl.*, pc.Name AS CityName FROM Parking_Lots pl INNER JOIN `Parking_Cities` pc ON pc.`CityID` = pl.`CityID` WHERE ID = :ID", array(":ID" => $lotid));
	}

	public function getKredinorLot($lotid)
	{
		return $this->db->fetch_row("SELECT * FROM `Kredinor` WHERE `KredinorID` = :ID", array(":ID" => $lotid));
	}

	public function getFileName($fileid)
	{
		return $this->db->fetch_row("SELECT `FileName` FROM `Document` WHERE `FileID` = :FileID", array(":FileID" => $fileid))->FileName;
	}

	public function getKredinorFiles($lotid)
	{
		
		$files_db = $this->db->fetch_all("SELECT * FROM `Document` WHERE `KredinorID` = :KredinorID AND Removed = 0", array(":KredinorID" => $lotid));

		if($files_db && $this->db->num_rows() > 0) {
			if(file_exists(INCLUDEPATH . "/media/$lotid/")) {

				return $files_db;

			} else {
				return false;
			}
		} else {
			return false;
		}
		
	}

	public function getCityLots($cityslug = '')
	{
		if(strlen($cityslug)>0) {
			return $this->db->fetch_all("SELECT pl.* FROM `Parking_Lots` pl
									INNER JOIN `Parking_Cities` pc ON pc.`CityID` = pl.`CityID`
									WHERE pc.`Slug` = :Slug", array(":Slug" => $cityslug));
		} else {

			$this->db->setLimit(100);

			return $this->db->fetch_all("SELECT pl.*, pc.`Name` FROM `Parking_Lots` pl
									INNER JOIN `Parking_Cities` pc ON pc.`CityID` = pl.`CityID`
									ORDER BY pc.`Name` ASC", array());
		}
		
	}
	public function Banner($path)
	{
		switch ($path) {
			case 'bestille-plass':
				return 'banner-bestillplass.jpg';
				break;
			case 'hvor-er-vi':
				return 'banner-hvorervi.jpg';
				break;
			case 'kontakt-oss':
				return 'banner-kontaktaoss.jpg';
				break;
			case 'kontrollavgift':
				return 'banner-kontrollavgift.jpg';
				break;
			case 'nyheter':
				return 'banner-nyheter.jpg';
				break;
			case 'om-oss':
				return 'banner-om-oss.jpg';
				break;
			default:
				return 'banner-default.jpg';
				break;
		}
	}

	public function calcBanners()
	{
		$num = glob("img/banner*.jpg");
		return $num !== false ? count($num) : 0;
	}

	// Markups
	public function getCities()
	{

		$this->db->setLimit(100);

		$markup = '<p>Her kan du bestille, betale, endre kjøretøy og avslutte leieforholdet. Du trenger ikke lenger å tenke på parkeringsbevis! Klikk deg inn via banneren over eller i toppmenyen og logg på og registrer deg, finn stedet du ønsker å leie og fyll inn ønsket informasjon. Så kan du starte å parkere.<br />Har du spørsmål, ring oss på tlf. 21 42 20 00 eller e-post til <a href="mailto:utleie@parknordic.no">utleie@parknordic.no</a></p>';
		$markup .= "<ul class=\"unstyled\">";
		foreach ($this->Cities() as $city) {
			$markup .= "<li><a href=\"/hvor-er-vi/".$city->Slug."/\">Steder vi holder til i ".ucfirst($city->Name)."</a></li>";
		}
		$markup .= "</ul>";
		echo $markup;
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function Kredinor($slug = '')
	{
		if(strlen($slug)>0) {
			return $this->db->fetch_row("SELECT * FROM Kredinor WHERE Slug = :Slug", array(":Slug" => $slug));
		} else {

			$this->db->setLimit(100);
			return $this->db->fetch_all("SELECT *, TRIM(SUBSTRING(Location,6)) AS City FROM Kredinor ORDER BY City");
		}
		
	}

	/**
	 * undocumented function
	 *
	 * @return void
	 * @author 
	 **/
	public function getKredinorDocuments ($slug = '')
	{
		return $this->db->fetch_all("	SELECT d.* FROM `Document` d
										INNER JOIN `Kredinor` k ON k.`KredinorID` = d.`KredinorID`
										WHERE k.`Slug` = :Slug AND d.`Removed` = 0", array(":Slug" => $slug));
	}
}

?>