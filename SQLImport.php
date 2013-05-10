<?php

class SQLImport{
	private $conn;
	public function load($db,$user,$pass){
		if(!($conn=mysql_connect( $db, $user, $pass))){
             die("errore not connect");
         }
	}
	
	public function import($file_name){
		$file=fopen($file_name,"r");
		$file_str=fread($file,filesize($file_name));
		$istr=explode(";",$file_str);
		foreach($istr as $is){
			$res=mysql_query($is);
		}
	}
	
	
}

$sql=new SQLImport();

$sql->load("127.0.0.1:3306","root","");
$sql->import("news.sql");

?>