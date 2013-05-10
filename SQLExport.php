<?php


class SQLExport{
	private $conn;
	public function load($db,$user,$pass){
		if(!($conn=mysql_connect( $db, $user, $pass))){
             die("errore not connect");
         }
	}
	
	public function formatType($value,$type){
		switch($type){
			case "char":
			return "'".$value."'";
			break;
			case "blob":
			return "'".$value."'";
			break;
			case "datetime":
			return "'".$value."'";
			break;
			case "text":
			return "'".$value."'";
			break;
			case "int":
			return $value;
			break;
			case "float":
			return $value;
			break;
		}
	}
	
	public function export($name,$file_name,$create_db=false,$toname=NULL){
	    mysql_select_db($name);
		$file=fopen($file_name,"w");
		if($create_db){
			fwrite($file,$this->createDB($name,$toname)."\n");
		}
		fwrite($file,$this->createTables()."\n");
		
	}
	
	public function createDB($dbname,$toname){
		return "CREATE DATABASE ".($toname==NULL?$dbname:$toname).";\nUSE ".($toname==NULL?$dbname:$toname).";";
	}
	
	public function createTables(){
		$res=mysql_query("SHOW TABLES");
		$ret="";
		while($arr=mysql_fetch_row($res)){
		   $ret.=$this->createTable($arr[0]);
		}
        return $ret;
	}
	
	public function createTable($table){
		$res=mysql_query("SHOW CREATE TABLE ".$table);
		$arr=mysql_fetch_row($res);
		return $arr[1].";\n".$this->inserts($table).";\n";
		
	}
	
	public function inserts($table){
		$res=mysql_query("SELECT * FROM ".$table);
		$ret="INSERT INTO ".$table." VALUES";
		$col=mysql_query("SHOW COLUMNS FROM ".$table);
		$types=array();
		while($arr_type=mysql_fetch_row($col)){
			$temp=explode("(",$arr_type[1]);
			$types[]=$temp[0];
		}
		while($arr=mysql_fetch_row($res)){
			
		   	$ret.=$this->insert($arr,$types).",";
		}
		return substr($ret,0,strlen($ret)-1);
	}
	
	public function insert($row2,$types){
		$ret2="(" ;
		
		for($x=0;$x<count($row2);$x++){
		    	$ret2.=($row2[$x]!=""?$this->formatType($row2[$x],$types[$x]):"null").",";
		}
		
		return substr($ret2,0,strlen($ret2)-1).")";
	}
}

$sql=new SQLExport();

$sql->load("127.0.0.1:3306","root","");
$sql->export("casealb","news.sql",true,"newcase");

?>