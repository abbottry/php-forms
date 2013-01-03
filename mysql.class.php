<?php
/**
 * MySql
 *
 * @package default
 * @author Ryan Abbott
 */
class MySql extends BaseClass
{
	/**
	 * __construct
	 *
	 * @author Ryan Abbott
	 */
	public function __construct($s = MYSQL_SERVER, $d = MYSQL_DB, $u = MYSQL_USER, $p = MYSQL_PASSWORD) {
		
		$this->server = $s;
		$this->database = $d;
		
		// Attempt to connect to the database from the config files parameters
		try {
			$this->connect($s, $d, $u, $p);
		}
		catch(Exception $e) {
			die($e);
		}
	}
	
	/**
	 * connect
	 *
	 * @param string $server 
	 * @param string $db 
	 * @param string $user 
	 * @param string $pw 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function connect($server, $db, $user, $pw) {
		$this->conn = @mysql_connect($server, $user, $pw);
		
		if(!$this->conn) {
			$message = "cannot connect to database because " . mysql_error();
			
			throw new Exception($message);
		}
		
		if(!@mysql_select_db($db, $this->conn)) {
			$message = "cannot connect to database with name '" . $db . "'";
			
			throw new Exception($message);
		}
	}
	
	/**
	 * query
	 *
	 * @param string $query 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function query($query, $log = true) {
		// clean up the edges and if any NULLs appear make sure they 
		// aren't surround with single quotes, defeats their purpose
		$query = str_replace("'NULL'", "NULL", ltrim($query));
		
		// run the query
		try {
			$this->result = $this->execute_query($query, $log);
		}
		catch (Exception $e) {
			die($e);
		}
		
		return $this->result;
	}
	
	/**
	 * mysql_query
	 *
	 * @param string $query 
	 * @param string $log 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function execute_query($query, $log) {
		// determine the type of query
		$type = strtolower(substr($query, 0, strpos($query," ")));
		
		$this->result = mysql_query($query);
		
		// only select and show queries use mysql_num_rows to return the 
		// number of rows returned, the rest use mysql_affected_rows
		if($this->result !== false) {
			if($type == "select" || $type == "show") {
				$rowcount = mysql_num_rows($this->result);
			}
			else {
				$rowcount = mysql_affected_rows();
			}
		}
		else {			
			$e = new Exception(mysql_error());
			$e->setQuery($query);
			throw $e;
			
			$rowcout = 0;
		}
			
		// return true if
		return $this->result;
	}
	
	/**
	 * firstRow
	 *
	 * @param string $result 
	 * @param string $type 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function firstRow($result = -1, $type = MYSQL_ASSOC) {
		if($result != -1) {
			return @mysql_fetch_array($result, $type);
		}
		else {
			$this->fetched = @mysql_fetch_array($this->result, $type);
			$this->free();

			return $this->fetched;
		}
	}
	
	/**
	 * fetch
	 *
	 * @param string $result 
	 * @param string $type 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function fetch($result = -1, $type = MYSQL_ASSOC) {
		if($result != -1) {
			return @mysql_fetch_array($result, $type);
		}
		else {
			$this->fetched = @mysql_fetch_array($this->result, $type);
			return $this->fetched;	
		}
	}
	
	/**
	 * numRows
	 *
	 * @param string $result 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function numRows($result = -1) {
		if($result != -1) {
			$this->result=$result;
		}
		
		return @mysql_num_rows($this->result);
	}
	
	/**
	 * insertId
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function insertId() {
		return mysql_insert_id();
	}
	
	/**
	 * free
	 *
	 * @param string $result 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function free($result = -1) {
		if($result != -1) {
			$this->result = $result;
		}
		
		@mysql_free_result($this->result);
	}
	
	/**
	 * next
	 *
	 * @param string $result 
	 * @return void
	 * @author Ryan Abbott
	 */
	public function next($result = -1)
	{
		if($result != -1) {
			$this->result = $result;
		}
		
		$return = false;
		
		if ($this->next = @mysql_fetch_array($this->result)) {
			$return = true;
		}
		
		return $return;
	}
	
	/**
	 * begin
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	function begin(){
		$null = $this->query("START TRANSACTION", $this->conn);
		
		return $this->query("BEGIN", $this->conn);
	}

	/**
	 * commit
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	function commit(){
		return $this->query("COMMIT", $this->conn);
	}

	/**
	 * rollback
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	function rollback(){
		return $this->query("ROLLBACK", $this->conn);
	}

	/**
	 * transaction
	 *
	 * @param string $q_array 
	 * @return void
	 * @author Ryan Abbott
	 */
	function transaction($q_array){
		$retval = 1;

		$this->begin();

		foreach($q_array as $qa){
			$result = $this->query($qa, $this->conn);
			
			if(mysql_affected_rows($this->conn) == 0) {
				$retval = 0;
			}
		}

		if($retval == 0){
			$this->rollback();
			
			return false;
		}
		else {
			$this->commit();
			
			return true;
		}
	}
	
	/**
	 * close
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function close() {
		mysql_close($this->conn);
	}
	
	/**
	 * __destruct
	 *
	 * @return void
	 * @author Ryan Abbott
	 */
	public function __destruct() {
		// no need to close the connection as it closes automatically
		// when the script is done executing
	}
}
?>