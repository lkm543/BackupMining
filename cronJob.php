<?php
/*
Date: 2018/06/26
Author: Robi
Topic:
    
Reference:

*/

date_default_timezone_set("Asia/Taipei").

#require_once(dirname(dirname(__FILE__))."/filename.php");
require_once("./database.php");
require_once("./nano.php");
require_once("./ethfan.php");
#require_once("./temp/f2pool.php");
#require_once("./temp/uupool.php");
#require_once("./temp/dwarfpool.php");    ####

$db = new db();
$nano = new nano();
$ethfan = new ethfan();
#$f2pool = new f2pool();
#$uupool = new uupool();
#$dwarfpool = new dwarfpool();    ####


$db->DBConnect();
$result = $db->selectAll();

##MySQL result to array
$ResultArray = array();
while($line = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	#print_r($line);echo '<br><br>';
	$ResultArray[] = $line;
}

$Status = array(
	1 => "Fine",
	2 => "Slow",
	3 => "Shut Down",
	4 => "API Error",
);

$i=0;
while($i<$result->num_rows){
	####或許該要先檢查$ResultArray[$i]["Comment"]來確定機台是否不在或是離現線?
	
	if($ResultArray[$i]["Pool"]=="nano"){
		
		##API require
		$nano->reset();
		$nano->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");    #from generalPool.php    #透過抽象函式預載基本資料
		$nano->getDataFromPool();
		
		##Modify the levels of Status
		$ResultArray[$i]["Status"] = 0;
		if(!$nano->ErrorFlag){
			$ResultArray[$i]["Reported"] = $nano->ReportedHashRate;
			$ResultArray[$i]["PoolHashRate"] = $nano->HashRate_LongTerm;
			
			##1=="Fine", 2=="Slow", 3=="Shut Down", 4=="API Error";
			if($ResultArray[$i]["Reported"]==0){
				$ResultArray[$i]["Status"] = 3;
			}elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
				$ResultArray[$i]["Status"] = 2;
			}else{
				$ResultArray[$i]["Status"] = 1;
			}
		}else{
			#echo("Error!!");
			$ResultArray[$i]["Status"] = 4;
		}
		
		##Update MySQL
		foreach($ResultArray[$i] as $key=>$value){
			#echo $key." ".$value."<br>";
			if($key=="Balance"){
				$cmd= "UPDATE gpu SET Balance = '$nano->Balance' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			#}elseif($key=="SpecifiedHashRate"){
				##理論算力
			}elseif($key=="Reported"){
				##
				$cmd= "UPDATE gpu SET Reported = '$nano->ReportedHashRate' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}elseif($key=="PoolHashRate"){
				##Real time Hash[Mh/s]
				$cmd= "UPDATE gpu SET PoolHashRate = '$nano->HashRate' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}elseif($key=="24Hrs"){
				##Worker Average Hashrate for 24 hour[Mh/s]
				$cmd= "UPDATE gpu SET 24Hrs = '$nano->HashRate_LongTerm' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			#}elseif($key=="Rig"){
				##Which machine?
			#}elseif($key=="Card"){
				##Which AVG?
			#}elseif($key=="Owner"){
			}elseif($key=="Status"){
				$cmd= "UPDATE gpu SET Status = '{$Status[$ResultArray[$i]["Status"]]}' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);				
			}elseif($key=="Comment"){
			#}elseif($key=="StartDate"){
				##Beginning date of contract
			}elseif($key=="UpdateTime"){
				$datetime = date("Y-m-d h:i:s", mktime());
				$cmd= "UPDATE gpu SET UpdateTime = '$datetime' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}else{
				#echo "this is a new data?";
			}
		}
	}elseif($ResultArray[$i]["Pool"]=="eth-tw"){
		
		##API require
		$ethfan->reset();
		$ethfan->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");    #from generalPool.php    #透過抽象函式預載基本資料
		$ethfan->getDataFromPool();
		
		##Modify the levels of Status
		$ResultArray[$i]["Status"] = 0;
		if(!$ethfan->ErrorFlag){
			$ResultArray[$i]["Reported"] = $ethfan->ReportedHashRate;
			$ResultArray[$i]["PoolHashRate"] = $ethfan->HashRate_LongTerm;
			
			##1=="Fine", 2=="Slow", 3=="Shut Down", 4=="API Error";
			if($ResultArray[$i]["Reported"]==0){
				$ResultArray[$i]["Status"] = 3;
			}elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
				$ResultArray[$i]["Status"] = 2;
			}else{
				$ResultArray[$i]["Status"] = 1;
			}
		}else{
			#echo("Error!!");
			$ResultArray[$i]["Status"] = 4;
		}
		
		##Update MySQL
		foreach($ResultArray[$i] as $key=>$value){
			#echo $key." ".$value."<br>";
			if($key=="Balance"){
				
				####貌似eth-tw並沒有各別worker的balance資料!!
				#$cmd= "UPDATE gpu SET Balance = '$ethfan->Balance' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				#$db->query($cmd);
			}elseif($key=="Reported"){
				##
				$cmd= "UPDATE gpu SET Reported = '$ethfan->ReportedHashRate' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}elseif($key=="PoolHashRate"){
				##Real time Hash[Mh/s]
				$cmd= "UPDATE gpu SET PoolHashRate = '$ethfan->HashRate' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}elseif($key=="24Hrs"){
				##Worker Average Hashrate for 24 hour[Mh/s]
				$cmd= "UPDATE gpu SET 24Hrs = '$ethfan->HashRate_LongTerm' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}elseif($key=="Status"){
				$cmd= "UPDATE gpu SET Status = '{$Status[$ResultArray[$i]["Status"]]}' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);				
			}elseif($key=="StartDate"){
				##Beginning date of contract
			}elseif($key=="UpdateTime"){
				$datetime = date("Y-m-d h:i:s", mktime());
				$cmd= "UPDATE gpu SET UpdateTime = '$datetime' WHERE Worker = '{$ResultArray[$i]["Worker"]}' AND Address= '{$ResultArray[$i]["Address"]}'";
				$db->query($cmd);
			}else{
				#echo "this is a new data?";
			}
		}
	}
	
	$i+=1;
}
?>