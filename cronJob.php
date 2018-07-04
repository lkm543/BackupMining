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
require_once("./f2pool.php");

$WarnRatio = 0.9;
$process_num = 10;

$db = new db();
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
#$uupool = new uupool();
#$dwarfpool = new dwarfpool();    ####

$children = array();

for($i = 0;$i<$result->num_rows; $i++) {
    $pid = pcntl_fork();
    if($pid == -1) {
        exit(1);
    } else if ($pid) {
    	//Parent
	    //如果超過，就等一秒鐘
	    //$children = $pid;
	    //echo (var_dump($children));
	    //echo (var_dump($pid));
	    while(count($children) >= $process_num){ 
	    //	echo (count($children));
	    //	echo (var_dump($children));
            echo "Maximum children allowed, waiting...<br>"; 
	    	echo (var_dump($pid));
            sleep(1); 
        } 
	    #echo(var_dump($children));
   } else {
   		//array_push($children,$pid);
   		$db2 = new db();
		$db2->DBConnect();

		$DataNow = new DataType();
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
				$nano = new nano();
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
				$ethfan = new ethfan();
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
			case 'f2pool':
				##API require
				$f2pool = new f2pool();
				$f2pool->reset();
				$f2pool->setBasicData($DataNow->Address,$DataNow->Worker,$DataNow->Coin);
				$f2pool->getDataFromPool();
				$APIError = $f2pool->ErrorFlag;
				
				if(!$APIError){
					$DataNow->ReportedHashRate = $f2pool->ReportedHashRate;
					$DataNow->HashRate_LongTerm = $f2pool->HashRate_LongTerm;
					$DataNow->Balance = $f2pool->Balance;
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

		$DataNow->Worker = $db2->escapeString($DataNow->Worker);
		$DataNow->Address = $db2->escapeString($DataNow->Address);
		$DataNow->Balance = $db2->escapeString($DataNow->Balance);
		$DataNow->ReportedHashRate = $db2->escapeString($DataNow->ReportedHashRate);
		$DataNow->HashRate_LongTerm = $db2->escapeString($DataNow->HashRate_LongTerm);
		$Status = $db2->escapeString($Status);
		$timeNow = $db2->escapeString($timeNow);


		$cmd= "UPDATE GPU SET Balance = '$DataNow->Balance' , Reported = '$DataNow->ReportedHashRate' , 24Hrs = '$DataNow->HashRate_LongTerm' , Status = '$StatusArray[$Status]' , UpdateTime = '$timeNow' WHERE Worker = '$DataNow->Worker' AND Address= '$DataNow->Address'";
		echo($cmd."<br>");
		$db2->query($cmd);

		//unset($children[array_search($pid,$children)]);
    	break; //直接出迴圈
   }
}

?>