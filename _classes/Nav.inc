<?php
/**
* 
*/
class Nav
{
	
	public $args = array();
	public $path = '';
	public $request_path = '';
	public $request_uri = '';
	public $siteroot = '';
	public $root = '';
	public $fileroot = '';
	public $include = '';

	public $staticMenuItems = array(
		"Bestille plass" => "http://parknordic-permit.mspace.giantleap.no/user.html#/login",
		"Agent" => "http://parknordic-permit.mspace.giantleap.no/agent.html#/login",
		//"Shop" => "http://parknordic-permit.mspace.giantleap.no/embedded-user-shop.html#/shop"
	);

	public 	$menu = '',
			$pages = array(),
			$bread_construct = array();

	private $filter,
			$db,
			$log;



	function __construct()
	{
		
		$this->siteroot = $_SERVER['HTTP_HOST'];
		$this->fileroot = realpath($_SERVER["DOCUMENT_ROOT"]) ."/";
		$this->request_path = isset($_GET['nav']) ? $_GET['nav'] : '';
		$this->request_uri = $_SERVER['REQUEST_URI'];
		$this->filter = InputFilter::obtain();

		try {
			$this->parse_request();
		} catch (Exception $e) {
			var_dump($e);
		}

	}

	public function pageClass()
	{
		return "loginPage";
	}

	public function setArgs($args = array())
	{
		$this->args = array_values($args);
	}

	public function getArgs($key = '')
	{
		return strlen($key)>0 ? ( isset($this->args[$key]) ? $this->filter->process( $this->args[$key] ) : false ) : $this->args;
	}

	public function setPath($path = '')
	{
		$this->path = $path;
	}

	
	public function setRoot($root)
	{
		if(stristr($root, "/")) {
			$array = explode("/", $root);
			$this->root = $array[0];
		} else {
			$this->root = $root;
		}
	}

	public function getRoot()
	{
		return $this->root;
	}
	public function getFileroot()
	{
		return $this->fileroot;
	}
	public function getInclude()
	{
		return $this->include;
	}
	
	public function getPath()
	{
		return "/".$this->path ."/";
		// .(count($this->args)>0 ? implode("/",$this->args) ."/" : '');
	}

	public function getPageInfo($pageurl)
	{
		return $this->pages[$pageurl];
	}

	public function gotSubMenu()
	{
		$page = $this->getPageInfo($this->getInclude());
		return (bool)isset($page['submenu']) || strpos($this->getInclude(),"_") !== false ? true : false;
	}

	public function parse_request()
	{

		$this->include = "home"; // Fallback to home
		$file_exists = false;

		$path = explode("/",$this->request_path);

		if(is_array($path) && count($path)>0) {
			foreach($path as $p) {
			
				$subs[] = $p;
				if(file_exists($this->fileroot ."_content/cnt_" .implode('_',$subs) .".php")) { 
					$include = implode('_',$subs);
					$file_exists = true;
				} 
			}
		}

		$this->include = !isset($include) ? $this->include : $include; // Default to base-include, else go with the sub-include

		$includePath = str_ireplace('_','\/',$this->include);
		$this->setPath(implode("/",$path));

		$args = explode('/',preg_replace("/$includePath/i", "", implode("/",$path), 1));
		
		foreach ($args as $key => $value) {
			if(strlen($value) == 0) {
				unset($args[$key]);
			}
		}

		$args = array_values($args);
	
		$this->setArgs($args);

		
		// Check that session is still valid
		if(isset($_SESSION['authed']) && $_SESSION['authed'] === true) {
			
			if(!isset($_SESSION['CREATED'])) {
				$_SESSION['CREATED'] = time();
			} elseif(time() - $_SESSION['CREATED'] > 2*60*60) { // kolla om sessionen är äldre än 2 timmar
				session_destroy();
				$_SESSION = array();
				header("Location: /login/?session_died");
			} else { // Sessionen är fortfarande aktiv, då refreshar vi den
				$_SESSION['CREATED'] = time();
				
				if($this->getInclude() == '') {
					header("Location: /");
					die;
				}

			}

		}
	 
		// Try and parse the querystrings in this requests also
		parse_str(parse_url($this->request_uri,PHP_URL_QUERY),$this->request_uri);

		$this->parseContentFiles();
	
	}

	public function isQsValue($key,$value)
	{
		return $this->request_uri[$key] == $value ? true : false;
	}
	
	public function isQsSet($key)
	{
		return (bool)isset($this->request_uri[$key]);
	}

	public function getUri()
	{
		return "/" . $this->getPath() .(count($this->request_uri) > 0 ? "/?" .http_build_query($this->request_uri) : '/');
	}

	public function includer($filetype) 
	{
		
		// if($this->user->getAccountType() == 2 && in_array("cnt_".$this->getInclude().".php", $this->disabled_for_affiliates)) {
		// 	header("Location: /404/");
		// 	die;
		// }
		// 

		switch ($filetype) {
			case 'action':
		
				if(file_exists($this->getFileroot() ."_action/act_" .$this->getInclude() .".inc")) {                        
					return $this->getFileroot() ."_action/act_" .$this->getInclude() .".inc";              
				} else {
					return false;
				}
				break;

			case 'library':
		
				if(file_exists($this->getFileroot() ."_library/lib_" .$this->getInclude() .".inc")) {                        
					return $this->getFileroot() ."_library/lib_" .$this->getInclude() .".inc";              
				} else {
					return false;
				}
				break;

			case 'content':
			default:

				if(file_exists($this->getFileroot() ."_content/cnt_" .$this->getInclude() .".php")) {                        
					return $this->getFileroot() ."_content/cnt_" .$this->getInclude() .".php";              
				} else {

					
					header("HTTP/1.0 404 Not Found");
					return $this->getFileroot() ."_content/cnt_404.php";
					
				}

				break;
		}
	}

	public function parseContentFiles()
	{
		
		// Dont include this pages in the menu
		$filter = array('.svn', '.', '..','.DS_Store','cnt_404.php','cnt_login.php', 'cnt_error.php', 'cnt_logout.php', 'cnt_home.php', 'cnt_kredinor.php', 'cnt_admin.php', 'cnt_bestille-plass.php', 'cnt_hvor-er-vi.php');
		$file_pages = array_values(array_diff(scandir($this->getFileroot() ."_content/"), $filter));
		
		foreach ($file_pages as $page) {

			$active = false;
			$sortorder = '';

			preg_match('/<!--(.*)-->/', file_get_contents($this->getFileroot() ."_content/" .$page), $matches);
			
			if($matches[1] > 0) {
				$sortorder = (int)trim($matches[1]);
			}

			preg_match('/^cnt_([\w\-]+)\.php$/', $page, $m);
			

			if($m[1] == $this->getInclude()) { // If this include is the active page, add the active bool
				$active = true;
			}

			if(strpos($m[1],"_") !== false) { // Got some subpages? Process them...
				
				$subpage = explode("_", $m[1]);
				$this->pages[$subpage[0]]["submenu"][$subpage[1]]["label"] = strpos($subpage[1],'-') !== false ? ucfirst( str_replace('-', ' ', $subpage[1]) ) : ucfirst($subpage[1]);
				$this->pages[$subpage[0]]["submenu"][$subpage[1]]["active"] = $active;
				$this->pages[$subpage[0]]["submenu"][$subpage[1]]["parent"] = $subpage[0];
				$this->pages[$subpage[0]]["submenu"][$subpage[1]]["sortorder"] = $sortorder;
				
				if($m[1] == $this->getInclude()) { // Set parent page as active if the subpage is active
					$this->pages[$subpage[0]]["active"] = true;
				}
				
			} else { // No subpages found, add them to pages array as regular page
				$this->pages[$m[1]]["label"] = strpos($m[1],'-') !== false ? ucfirst( str_replace('-', ' ', $m[1]) ) : ucfirst($m[1]);
				$this->pages[$m[1]]["active"] = $active;
				$this->pages[$m[1]]["sortorder"] = $sortorder;
			}
		}

	}

	public function cmp($a,$b)
	{
		return $a["sortorder"] > $b["sortorder"];
	}

	public function getMenu()
	{
		
		uasort($this->pages,array($this,"cmp"));	

		$this->menu = "<ul class=\"nav pull-right\">\n";
		foreach ($this->pages as $url => $page) {
			
			$classes = array();
			if($page["active"] === true) {
				$classes[] = "active";
			}

			if(isset($page["submenu"]) 
				&& count($page['submenu'])>0
			//	&& $page["active"] === true
			) {

				usort($page["submenu"],array($this,"cmp"));

				$classes[] = "dropdown";
			
				$submenu = "<ul class=\"dropdown-menu\">\n";
				$submenu .= "<li class=\"menu\">\n";
				$submenu .= "<ul>";
				foreach ($page["submenu"] as $suburl => $subpage) {
					$submenu  .= "<li".($subpage["active"] === true ? " class=\"active\"" : "").">";
					$submenu .= "<a href=\"/{$url}/{$suburl}/\">";
				//	$submenu .= isset($subpage['spanicon']) && strlen($subpage['spanicon'])>0 ? "<span class=\"icon16 ".$subpage['spanicon']."\"></span>" : "";
					$submenu .= ucfirst($subpage["label"]);
					$submenu .= "</a></li>\n";
				}
				$submenu .= "</ul></li>";
				$submenu  .= "</ul>\n";
			} else {
				$submenu = '';
			}

			$this->menu .= "<li class=\"".(count($classes)>0 ? implode(" ", $classes) : '')."\">";

			$this->menu .= "<a ".( strlen($submenu)>0 ? "class=\"dropdown-toggle\" data-toggle=\"dropdown\"" : "" )." href=\"/{$url}/\">";

			//$this->menu .= isset($page['spanicon']) && strlen($page['spanicon'])>0 ? "<span class=\"icon16 ".$page['spanicon']."\"></span>" : "";
			// Add page name to a href
			$this->menu .= ucfirst($page["label"]);

			$this->menu .= strlen($submenu)>0 ? " <b class=\"caret\"></b>" : "";
			$this->menu .= "</a>\n";
			$this->menu .= strlen($submenu)>0 ? $submenu : '';

			$this->menu .= "</li>";
		}

		if(isset($this->staticMenuItems) && count($this->staticMenuItems)>0) {
			foreach ($this->staticMenuItems as $smenu_label => $smenu_url) {
				$this->menu .= "<li>";
				$this->menu .= "<a href=\"{$smenu_url}\">{$smenu_label}</a>";
				$this->menu .= "</li>";
			}
		}
		$this->menu .= "\n</ul>";

		echo $this->menu;
	}

	public function getSideMenu()
	{
		$this->sidemenu = "<ul>\n";
		foreach ($this->pages as $url => $page) {
			
			$classes = array();

			if($page["active"] === true) {
				$classes[] = "current";
			}
			
			if(isset($page["submenu"]) 
				&& count($page['submenu'])>0
			//	&& $page["active"] === true
			) {


				$classes[] = "hasUl";
				//$submenu .= "<ul class=\"sub".($page["active"] === true ? " drop" : "")."\">";
				$submenu .= "<ul class=\"sub".($page["active"] === true ? " expand" : "")."\" style=\"width:100%\">";
				foreach ($page["submenu"] as $suburl => $subpage) {
					$submenu  .= "<li>";
					$submenu .= "<a".($subpage["active"] === true ? " class=\"current\"" : "")." href=\"/{$url}/{$suburl}/\">";
					$submenu .= isset($subpage['spanicon']) && strlen($subpage['spanicon'])>0 ? "<span class=\"icon16 ".$subpage['spanicon']."\"></span>" : "";
					$submenu .= ucfirst($subpage["label"]);
					$submenu .= "</a></li>\n";
				}
				$submenu .= "</ul>\n";
			} else {
				$submenu = '';
			}

			$this->sidemenu .= "<li>";

			$this->sidemenu .= "<a ".(count($classes)>0 ? "class=\"".implode(" ", $classes)."\"" : '')." href=\"/{$url}/\">";

			$this->sidemenu .= isset($page['spanicon']) && strlen($page['spanicon'])>0 ? "<span class=\"icon16 ".$page['spanicon']."\"></span>" : "";
			// Add page name to a href
			$this->sidemenu .= ucfirst($page["label"]);

			$this->sidemenu .= strlen($submenu)>0 ? " <span class=\"hasDrop icon16 icomoon-icon-arrow-down-2\"></span>" : "";
			$this->sidemenu .= "</a>\n";
			$this->sidemenu .= strlen($submenu)>0 ? $submenu : '';

			$this->sidemenu .= "</li>";
		}
		$this->sidemenu .= "\n</ul>";

		echo $this->sidemenu;
	}

}

?>