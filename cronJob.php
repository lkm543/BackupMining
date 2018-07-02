<?php
/*
Date: 2018/07/02
Detail: CronJob to update the date from pool regularly!
*/

date_default_timezone_set("Asia/Taipei").

require_once("./database.php");
require_once("./DataType.php");
require_once("./nano.php");
require_once("./ethfan.php");

$db = new db();
$nano = new nano();
$ethfan = new ethfan();
$DataNow = new DataType();
#$f2pool = new f2pool();
#$uupool = new uupool();
#$dwarfpool = new dwarfpool();    ####

$WarnRatio = 0.9;

$db->DBConnect();
$result = $db->selectAll();

##MySQL result to array
$ResultArray = array();
while($line = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	#print_r($line);echo '<br><br>';
	$ResultArray[] = $line;
}

$StatusArray = array(
	1 => "Fine",
	2 => "Slow LongTerm",
	3 => "Slow Report",
	4 => "Shut Down",
	5 => "API Error",
);


#echo(var_dump($ResultArray));

$i=0;
while($i<$result->num_rows){
	####或許該要先檢查$ResultArray[$i]["Comment"]來確定機台是否不在或是離現線?
	####不需要 每次重新載一次才知道有沒有重新上線之類的 by:LKM

	$DataNow->resetData();
	#echo(var_dump($DataNow));
	$DataNow->Address = $ResultArray[$i]["Address"];
	$DataNow->Worker = $ResultArray[$i]["Worker"];
	$DataNow->Coin = $ResultArray[$i]["Coin"];
	$SpecifiedHashRate = $ResultArray[$i]["SpecifiedHashRate"];
	$Status = 0;
	$APIError = true;

	$PoolNow = $ResultArray[$i]["Pool"];

	switch ($PoolNow) {
		case 'nano':
			##API require
			$nano->reset();
			$nano->setBasicData($DataNow->Address,$DataNow->Worker,$DataNow->Coin);
			$nano->getDataFromPool();
			$APIError = $nano->ErrorFlag;

			if(!$APIError){
				$DataNow->ReportedHashRate = $nano->ReportedHashRate;
				$DataNow->HashRate_LongTerm = $nano->HashRate_LongTerm;
				$DataNow->Balance = $nano->Balance;
			}
			break;
		case 'eth-tw':
			##API require
			$ethfan->reset();
			$ethfan->setBasicData($DataNow->Address,$DataNow->Worker,$DataNow->Coin);
			$ethfan->getDataFromPool();
			$APIError = $ethfan->ErrorFlag;
			
			if(!$APIError){
				$DataNow->ReportedHashRate = $ethfan->ReportedHashRate;
				$DataNow->HashRate_LongTerm = $ethfan->HashRate_LongTerm;
				$DataNow->Balance = $ethfan->Balance;
			}
			break;
		default:
			# code...
			break;
	}

	#Modify status
	if(!$APIError){
		if($DataNow->ReportedHashRate==0){
			$Status = 4;
		}elseif($DataNow->ReportedHashRate<$WarnRatio*$SpecifiedHashRate){
			$Status = 3;
		}elseif ($DataNow->HashRate_LongTerm<$WarnRatio*$SpecifiedHashRate) {
			$Status = 2;
		}
		else{
			$Status = 1;
		}
	}else{
		#API Error!!!
		$Status = 5;
	}

	$timeNow = date("Y-m-d H:i:s");

	$cmd= "UPDATE GPU SET Balance = '$DataNow->Balance' , Reported = '$DataNow->ReportedHashRate' , 24Hrs = '$DataNow->HashRate_LongTerm' , Status = '$StatusArray[$Status]' , UpdateTime = '$timeNow' WHERE Worker = '$DataNow->Worker' AND Address= '$DataNow->Address'";
	echo($cmd."<br>");
	$db->query($cmd);
	
	$i+=1;
}
?>