<?php
class Database {
	public static $db;
	public static $con;
	function Database(){
		$this->user="daft_usr_test";$this->pass="qpalzm1029";$this->host="localhost";$this->ddbb="daft_inventario";
	}

	function connect(){
		$con = new mysqli($this->host,$this->user,$this->pass,$this->ddbb);
		return $con;
	}

	public static function getCon(){
		if(self::$con==null && self::$db==null){
			self::$db = new Database();
			self::$con = self::$db->connect();
		}
		return self::$con;
	}
	
}
?>
