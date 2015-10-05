<?php
// Copyright SQCRM. For licensing, reuse, modification and distribution see license.txt  
/**
* New DataObject class to use the functionality of Doctrine 
* Inspired by RADRIA DataObject 
* @see radria.sqlfusion.com
*
*/
class DataObject extends BaseObject {
	//-- values array to hold key value data
	protected $values = Array();
	//-- holds the key for values array
	public $fields;
	//-- holds the db conn object from Doctrine
	public $dbcon ;
	//-- holds the table name
	public $table ;
	//-- holds the primary_key of a table
	protected $primary_key = '';
	//-- holds the result set of a query
	public $result ;
	//-- holds the string sql query
	public $sql_query = '';
	
	/**
	* constructor function
	* @param object $conn
	* @param string $table_name
	*/
	public function __construct($conn=NULL, $table_name="") {
		if (is_null($conn)) { $conn = $GLOBALS['conn']; }
	}
	
	/**
	* Magic method __set
	* @param string $field
	* @param string $value
	*/
	public function __set($field, $value) {
		$this->values[$field] = $value;
	}
  
	/**
	* Magic method __get
	* @param string $field
	* @return mix value
	*/
	public function __get($field) {
		$value = "";
		if (isset($this->values[$field])) {
			if (strlen($this->values[$field]) > 0) {
				$value = $this->values[$field] ;
			}
		}
		return $value;
	}
  
	/**
	* function set DB connection
	* @param object $conn
	*/
	public function setDbConnection($conn) {
		$this->dbcon = $conn ;
	}
  
	/**
	* function to set the query result
	* @param object $result
	* The result is the PDO object of prepare statement
	*/
	public function set_result($result) {
		$this->result = $result ; 
	}
  
	/**
	* function to set table
	* @param string $tablename
	* @return boolean
	*/
	public function setTable($tablename='') {
		if (!empty($tablename)) {
			$this->table = $tablename;
			return true;
		} else {
			return false;
		}
	}
  
	/**
	* function to get the table name
	* @return mix
	*/
	public function getTable() {
		if (!empty($this->table)) {
			return $this->table;
		} else {
			return false;
		}
	}
	
	/**
	* function to set the primary key
	* @param string $primary_key
	*/
	function setPrimaryKey($primary_key) {
		$this->primary_key = $primary_key ;
	}
  
	/**
	* function to get the primary key
	* @return string
	*/
	public function getPrimaryKey() {
		if (!empty($this->primary_key)) {
			return $this->primary_key ;
		} else {
			$this->setPrimaryKey("id".$this->getTable());
			return "id".$this->getTable();
		}
	}
  
	/**
	* function to add/insert record
	* @see self:: insert()
	*/
	public function add() {
		$table = $this->getTable();
		$this->insert($table,$this->values);
	}
  
	/**
	* function insert 
	* @param string $table
	* @param array $data
	*/
	public function insert($table,$data) {
		$this->getDbConnection()->insert($table,$data);
	}
  
	/**
	* method addNew, should be called before add() is called and values are set
	* cleans the values and result currently holds by the object
	*/
	function addNew() {
		$this->cleanValues();
		$this->cleanResult();
	}
	
	/**
	* function to clean the currently hold values by the object
	*/
	function cleanValues() {
		$this->values = Array();
	}
	
	/**
	* function to clean the currently hold result object by the object
	*/
	function cleanResult() {
		$this->result = null;
	}
	
	/**
	* function to get the row values by primary key
	* @param mix $id
	* @return values
	*/
	public function getId($id) {
		$sql = "select * from `".$this->getTable()."` where `".$this->getPrimaryKey()."` = :pk";
		$this->result = $this->getDbConnection()->prepare($sql);
		$this->result->bindValue(":pk", $id);
		$this->result->execute();
		return $this->values = $this->result->fetch();
	}
	
	/**
	* get the num of rows returned by the last executed query
	* @return integer
	*/
	function getNumRows() {
		return $this->result->rowCount();
	}
	
	/**
	* function to check if the last query has values
	* @see self::getNumRows
	* @return boolean
	*/
	function hasData() {
		if ($this->getNumRows() > 0){
			return true;
		} else { return false ; }
	}
	
	/**
	* function to get all values of a table 
	* @param string orderby
	*/
	function getAll($orderby="") {
		$qry_orderby = "";
		if (!empty($orderby)) { $qry_orderby = " order by ".$orderby; }
		$sql = "select * from `".$this->getTable()."`".$qry_orderby;
		$this->result = $this->getDbConnection()->prepare($sql);
		$this->result->execute();
	}
	
	/**
	* function to set the cursor to the next row
	* @return array values
	*/
	public function next() {
		return $this->values = $this->result->fetch();
	}

	public function getAllArray($orderby="") {
		$qry_orderby = "";
		if (!empty($orderby)) { $qry_orderby = " order by ".$orderby; }
		$sql = "select * from `".$this->getTable()."`".$qry_orderby;
		$this->result = $this->getDbConnection()->prepare($sql);
		$this->result->execute();
		return $this->result->fetchAll();
	}
	
	/**
	* function to delete a record
	* @param mix $data
	*/
	public function delete($data,$table="") {
		if (empty($table)) $table = $this->getTable() ;
		if (is_array($data)) {
			$this->getDbConnection()->delete($table,$data);
		} else {
			$pk = $this->getPrimaryKey() ;
			$this->getDbConnection()->delete($table,array($pk=>$data));
		}
	}
	
	/**
	* function to execute update query
	* @param mix $identifier
	* @param string $table
	* @param array $data
	*/
	public function update($identifier,$table="",$data=array()) {
		if ($table == "") $table = $this->getTable() ;
		if (count($data) == 0 ) $data = $this->values ;
		if (is_array($identifier)) $where = $identifier ;
		else $where= array($this->getPrimaryKey()=>$identifier);
		$this->getDbConnection()->update($table,$data,$where);
	}
	
	/**
	* function to get the last insert id
	*/
	public function getInsertId() {
		return $this->getDbConnection()->lastInsertId();
	}
	
	/**
	* function to set the sql query
	* @param string $qry
	*/
	public function setSqlQuery($qry) {
		$this->sql_query = trim($qry) ;
	}
	
	/**
	* function to get the sql query which is set
	* @return sql_query
	*/
	function getSqlQuery() {
		return $this->sql_query ;
	}
	
	/**
	* function to get the DB connection
	*/
	public function getDbConnection() {
		return $GLOBALS['conn'] ;
	}
	
	/**
	* function to execute a query
	* @param string $qry
	* @param array $params
	* @param array $types
	*/
	public function query($qry,$params=array(), $types=array()) {
		$this->result = $this->getDbConnection()->executeQuery($qry,$params,$types);
	}
	
}