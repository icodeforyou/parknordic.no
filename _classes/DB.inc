<?php 
/**
* 
*/
class DB
{
	public $DBH;
	public $stmt;
	public $servertype = '';
	public $num_rows = 0;
	public $total_num_rows = 0;
	public $offset = 0;
	public $limit = 0;
	public $debug = true;
	public $error = '';
	private $state = "dev";
	
	private static $instance;

	/**
	 * Try and connect to DB as a PDO
	 *
	 * @return void
	 * @author 
	 **/
	function __construct()
	{
		set_exception_handler(array($this, 'error_handler'));

		try {

			$this->servertype = "mysql";
			$this->DBH = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_USER_PASS, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
			$this->DBH->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); 

		} catch (Exception $e) {
			$this->error_handler($e);
		}
		

	}

	public static function obtain() { 
		self::$instance = !is_null(self::$instance) ? self::$instance : new self; 
		return self::$instance; 
	}

	/**
	 * Custom Error handler, prevents error msg:s to be outputted to end user
	 *
	 * @return void
	 * @author 
	 **/
	public function error_handler($e)
	{
		//$this->debug = $this->stmt->errorInfo();
		
		switch ($this->state) {
			case 'prod':
				echo "An error occured, please contact our support";
				$this->error = $e;
				break;
			case 'dev':
			default:
				//header("Content-type: text/plain");
				$this->error = $e;
				$msg = "---------------------------------- ERROR -------------------------------------------\n";
				$msg .= "({$e->getCode()}) {$e->getMessage()}";
				$msg .= "\nFile: {$e->getFile()} Row: {$e->getLine()}";
				$msg .= isset($this->stmt) ? "\nErrorCode: ".var_export($this->stmt->errorInfo(),true) : '';
				$msg .= isset($this->stmt) ? "\nDebug: ". var_export($this->stmt->errorInfo(), true) : '';
				//$msg .= $this->debug !== false ? "\n".var_export($this->debug) : "";
				$msg .= $this->debug !== false && is_object($this->stmt) ? "\n".var_export($this->stmt->debugDumpParams(),true) : "";
				$msg .= "\n-------------------------------------------------------------------------------------\n";
				echo ($msg);
				break;
		}
		die();

	}

	public function getError($type = "message")
	{
		switch ($type) {
			case 'file':
				return $this->error->getFile() .", Row: ".$this->error->getLine();
				break;
			case 'code':
				return $this->error->getCode();
				break;
			case 'message':
			default:
				return $this->error->getMessage();
				break;
		}
	}
	
	/**
	 * House keeping
	 *
	 * @return void
	 * @author 
	 **/
	function __destruct() {
		$this->DBH = null;
	}

	/**
	 * Main Query function - dont access directly
	 * Makes preperation and binding of parameters
	 * @return void
	 * @author 
	 **/
	private function prep($query = '', $parameters=array())
	{
		
		if((strpos($query, "LIMIT") === false && strpos($query, "SELECT") !== false)
			&& $this->servertype == "mysql"
			&& $this->limit > 0) {
			$query = rtrim($query, ";"); // Remove ev trailing sql-end
			$query .= "\nLIMIT :offset,:limit";

			// Replace SELECT with SELECT SQL_CALC_FOUND_ROWS
			$query = preg_replace('/SELECT/', "SELECT SQL_CALC_FOUND_ROWS", $query, 1);
			
			//("SELECT", "SELECT SQL_CALC_FOUND_ROWS", $query);

			//$this->DBH->setAttribute(array(PDO::MYSQL_USE_BUFFERED_QUERY=>TRUE)); 
		}

		try {
			$this->stmt = $this->DBH->prepare($query);
		} catch (PDOException $e) {
			$this->error_handler($e);
		}
		
		if(count($parameters)>0) {
			
			foreach ($parameters as $key => $var) {

				switch ($var) {
					// case is_numeric( $var ):
					// 	$this->stmt->bindValue($key,$var, PDO::PARAM_INT);
					// 	break;
					case is_bool( $var ):
						$this->stmt->bindValue($key,$var, PDO::PARAM_BOOL);
						break;
					case is_null( $var ):
						$this->stmt->bindValue($key,$var, PDO::PARAM_NULL);
						break;
					
					default:
						$this->stmt->bindValue($key,$var, PDO::PARAM_STR);
						break;
				}

			
			}

		}

		if(strpos($query, "SELECT") !== false
			&& $this->servertype == "mysql"
			&& $this->limit > 0) {
			$this->stmt->bindValue(":offset",$this->offset, PDO::PARAM_INT);
			$this->stmt->bindValue(":limit",$this->limit, PDO::PARAM_INT);
		}
		
		return true;
		
	}

	public function num_rows()
	{
		return $this->num_rows;
	}

	public function total_num_rows()
	{
		return $this->total_num_rows;
	}

	/**
	 * Set custom offset
	 * Defaults to standard value
	 * @return void
	 * @author 
	 **/
	public function setOffset($offset='')
	{
		$this->offset = $offset;
	}

	/**
	 * Set limit
	 * Defaults to standard value
	 * @return void
	 * @author 
	 **/
	public function setLimit($limit='')
	{
		$this->limit = $limit;
	}


	public function prepBindings()
	{

		foreach(range(0, $this->stmt->columnCount() - 1) as $column_index) {
			$meta[] = $this->stmt->getColumnMeta($column_index);
		}
		var_dump($meta);	
	}

	/**
	 * Begins a INNODB transaction, turnes autocommit OFF
	 *
	 * @return void
	 * @author 
	 **/
	public function do_transaction()
	{
		return $this->DBH->beginTransaction();
	}

	/**
	 * Does a full rollback of started transaction
	 * Will turn autocommit back on
	 *
	 * @return void
	 * @author 
	 **/
	public function do_rollback()
	{
		return $this->DBH->rollBack();
	}

	/**
	 * Commits currenct transaction
	 * will turn autocommit back on
	 *
	 * @return void
	 * @author 
	 **/
	public function do_commit()
	{
		return $this->DBH->commit();
	}

	/**
	 * Fetches a single row
	 *
	 * @return void
	 * @author 
	 **/
	public function fetch_row($query = '', $parameters=array())
	{

		// Prepare and execute
		$this->prep($query,$parameters);

		try {
			if($this->stmt->execute()) {

				if(strpos($query, "SELECT") !== false
					&& $this->servertype == "mysql") {
					$rs = $this->DBH->query("SELECT FOUND_ROWS()");
					$this->total_num_rows = (int) $rs->fetchColumn();
				}

				// Set how many rows the query resulted in
				$this->num_rows = $this->stmt->rowCount();

				return $this->stmt->fetchObject();

			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->error_handler($e);
		}

	}

	/**
	 * Fetches all rows
	 * Adds limit and offset code if not in query
	 * @return void
	 * @author 
	 **/
	public function fetch_all($query = '', $parameters=array(), $bindings = array())
	{
		
		// Prepare and execute
		$res = $this->prep($query,$parameters);

		try {
			if($this->stmt->execute()) {

				if(strpos($query, "SELECT") > 0
					&& $this->servertype == "mysql") {
					$rs = $this->DBH->query("SELECT FOUND_ROWS()");
					$this->total_num_rows = (int) $rs->fetchColumn();
				}

				// Set how many rows the query resulted in
				$this->num_rows = $this->stmt->rowCount();
				
				while ($object = $this->stmt->fetchObject()) {
					$result[] = $object;
				}
				return isset($result) ? $result : false;

			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->error_handler($e);
		}
	}

	public function query($query = '', $parameters=array())
	{
		// Prepare and execute
		$res = $this->prep($query,$parameters);
		try {
			if($this->stmt->execute()) {
				
				$this->num_rows = $this->stmt->rowCount();
				return $this->num_rows;

			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->error_handler($e);
		}
	}

	public function insert($query = '', $parameters=array())
	{
		// Prepare and execute
		$res = $this->prep($query,$parameters);
		try {
			if($this->stmt->execute()) {
				
				$this->num_rows = $this->stmt->rowCount();
				return $this->DBH->lastInsertId();			

			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->error_handler($e);
		}
	}

	public function update($query = '', $parameters = array())
	{
		$res = $this->prep($query,$parameters);
		try {
			if($this->stmt->execute()) {
				
				$this->num_rows = $this->stmt->rowCount();
				return $this->stmt->rowCount();	

			} else {
				return false;
			}
		} catch (PDOException $e) {
			$this->error_handler($e);
		}
	}

}
?>