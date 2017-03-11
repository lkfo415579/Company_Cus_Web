<?php
//use mysql database
//一

class DBConnectionPDO{
	public $db_con;
	
	public function __construct($db_name,$db_user="root",$db_password="",$db_server="localhost"){
		$dsn = "mysql:host={$db_server};dbname={$db_name}";
		try {
			$this->db_con = new PDO($dsn, $db_user, $db_password);
			$this->db_con->exec('SET NAMES utf8,CHARACTER_SET_CLIENT=utf8,CHARACTER_SET_RESULTS=utf8;');
		} catch (PDOException $e) {
			$this->db_con = null;
			//var_dump($e);
			throw new Exception('Cannot connect to database.');
		}
	}
	
	public function StartConnection(){
		return null;
	}
	
	public function CloseConnection(){
		$this->db_con = null;
	}
	
	// return boolean or result set
	public function ExecuteQuery($query){
		return $this->db_con->query($query);
	}
	
	public function GetNextRow(&$sth){
		return $sth->fetch(PDO::FETCH_ASSOC);
	}
	
	public function GetAllRows(&$sth){
		return $sth->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function GetNumRows(&$sth){
		return $sth->rowCount();
	}
	
	public function GetLastInsertID(){
		return $this->db_con->lastInsertId();
	}
	
	public function FreeResultSet(&$sth){
		$sth = null;
	}
	
	public function PrepareQuery($query){
		return $this->db_con->prepare($query);
	}
	
	public function ExecutePreparedQuery($sth,$params){
		return $sth->execute($params);
	}
	
	public function FormatQueryElement($elem){
		$elem = str_replace("\\","\\\\",$elem);
		$elem = str_replace("'","\\'",$elem);
		$elem = str_replace('"','\"',$elem);
		return $elem;
	}
}
?>