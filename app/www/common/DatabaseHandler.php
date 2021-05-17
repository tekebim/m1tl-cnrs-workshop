<?php

class DatabaseHandler{
	private $databaseDescriptor;
	private $mysqli;
	private $connectionEstablished;
	private $str_time;
	private static $logFile = "/var/log/www/php/slow_query.log";
	private static $hurdle = 0.8;

	public function __construct($dbHost, $dbUser, $dbPassword, $dbName, $port){
		$this->mysqli = mysqli_init();
		$this->mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 30);
		$this->mysqli->real_connect($dbHost, $dbUser, $dbPassword, $dbName, $port);
		$this->str_time = microtime(true); 
		$this->mysqli->set_charset("utf8");
		$this->connectionEstablished = !mysqli_connect_errno();
		return $this->hasConnection();
	}

	public function hasConnection(){
		return ($this->connectionEstablished == true);
	}
	public function escapeString($string){
		return $this->mysqli->real_escape_string($string);
	}
	public function executeQuickQuery($SQL){
		return $this->executeQuery($SQL,false);
	}
	public function resetStartTime()
	{
		$this->str_time = microtime(true);
	}
	public function getElaspedTime()
	{
		return microtime(true) - $this->str_time;
	}
	public function isSlowQuery()
	{
		return ( $this->getElaspedTime() > self::$hurdle);
	}

	public function executeQuery($SQL, $wantResultSet = true){
		if(!$this->hasConnection()){return null;}
		$result = $this->mysqli->query($SQL);
		if($result ===false){error_log($SQL);error_log($this->mysqli->error);error_log($this->mysqli->sqlstate);}
		if($wantResultSet){
			return $result;	
		}	 	
		return true;
		
	}
	
	public function getEnumValues( $table, $field ){
		if(!$this->hasConnection()){return null;}
		$r = $this->mysqli->query( "SHOW COLUMNS FROM {$table} WHERE Field = '{$field}'" )->fetch_assoc();
		$type = $r["Type"];
		preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
		$enum = explode("','", $matches[1]);
		return $enum;
	}

	public function logForTheRecord($query){
        	//file_put_contents(self::$logFile,date("Ymd h:i:s")."	;	".$this->getElaspedTime()."	;	".$query."\n",FILE_APPEND);
	}



	private function executeMultiQuery($SQL){
		if(!$this->hasConnection()){return null;}
		$this->resetStartTime();
		$temp = $this->mysqli->real_query($SQL);
		if($temp ==false){error_log($SQL);error_log($this->mysqli->error);error_log($this->mysqli->sqlstate);die("Error while trying to call store proc. ".$SQL);}
			$rs = new ResultSet();
			$result = $this->mysqli->store_result();
			//$result = $this->mysqli->use_result(); 
			while ($row = $result->fetch_assoc()){
				$rs->addResultRow($row);
			}
			$result->close();
			if($this->mysqli->more_results()){
			  while($this->mysqli->next_result());
			}
			$rs->MoveFirst();
			/*
			if( $this->isSlowQuery() ){
				self::logForTheRecord($SQL);	
			}
			*/
			return $rs;
	}


	public function executeInsertQueryAutoincrement($SQL){
		if(!$this->hasConnection()){return null;}
		$result = $this->mysqli->query($SQL);
		//$resultLink = mysql_query($SQL, $this->databaseDescriptor);
		return $this->mysqli->insert_id;
	}

	public static function buildSelectQuery($tableName, $selectField=array("*"), $conditions=array(), $orderby=array()){
		$return = "Select ".implode(',',$selectField)." from ".$tableName;
		if(count($conditions)>0){
			$return .= " WHERE ".implode(' AND ',$conditions);
		}
		if(count($orderby)>0){
			$return .= " ORDER BY ".implode(',',$orderby);
		}
		return $return;
	}
}
?>
