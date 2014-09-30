<?php

class DB {
	
	public static $instance = null;
	
	public static  function init(){
		if(null == Db::$instance){
			$DBSTR = "mysql:dbname=".DB_NAME;
			$pdo = new PDO($DBSTR , DB_USER,DB_PASSWORD);
			Db::$instance = new NotORM($pdo);
		}
		
		return Db::$instance;
	}
	
}

?>