<?php
	
	//$worker = $_GET['worker'];

	$db = new db();
	$db->DBConnect();
	$result = $db->selectAll();

	$address = $db->escapeString($address);

	$sql = "select GPU_Index,Pool,Coin,Balance,SpecifiedHashRate,Reported,24Hrs,UpdateTime,PoolTime from `GPU` WHERE Owner='$Owner'";
	$result = $db->query($sql);

	//echo(var_dump($result));
	 
	// die if SQL statement failed
	if (!$result) {
	  http_response_code(404);
	  die(mysqli_error());
	}

	$CardAmounts = $db->numRows($result);
	$ResultArray = array();
	while($line = $db -> fetchArray($result, MYSQLI_ASSOC)){
	    $ResultArray[] = $line;
	}

	$sql2 = "SELECT * FROM `Analysis` ORDER BY UpdateTime DESC LIMIT 1";
	$result2 = $db->query($sql2);
	//echo(var_dump($result2));
	$AnalysisArray = array();
	while($line = $db -> fetchArray($result2, MYSQLI_ASSOC)){
	    $AnalysisArray[] = $line;
	}


	$NCoins = count($Coins);
	$Coins[0]['EarningRate']= floatval($AnalysisArray[0]['EarningETH']);
	$Coins[0]['CoinUSD']= floatval($AnalysisArray[0]['ETHUSD']);
	$Coins[1]['EarningRate']= floatval($AnalysisArray[0]['EarningETC']);
	$Coins[1]['CoinUSD']= floatval($AnalysisArray[0]['ETCUSD']);
	$Coins[2]['EarningRate']= floatval($AnalysisArray[0]['EarningZEC']);
	$Coins[2]['CoinUSD']= floatval($AnalysisArray[0]['ZECUSD']);
	$Coins[3]['EarningRate']= floatval($AnalysisArray[0]['EarningAION']);
	$Coins[3]['CoinUSD']= floatval($AnalysisArray[0]['AIONUSD']);
	$Coins[4]['EarningRate']= floatval($AnalysisArray[0]['EarningXMC']);
	$Coins[4]['CoinUSD']= floatval($AnalysisArray[0]['XMCUSD']);
	$Coins[5]['EarningRate']= floatval($AnalysisArray[0]['EarningXMR']);
	$Coins[5]['CoinUSD']= floatval($AnalysisArray[0]['XMRUSD']);

	//echo (var_dump($ResultArray));
	for ($i=0;$i<$CardAmounts;$i++) {
		$Coin = $ResultArray[$i]['Coin'];
		$Pool = $ResultArray[$i]['Pool'];
		$Balance = $ResultArray[$i]['Balance'];
		$SpecifiedHashRate = $ResultArray[$i]['SpecifiedHashRate'];
		$ReportHashRate = $ResultArray[$i]['Reported'];
		$AverageHashRate = $ResultArray[$i]['24Hrs'];

		for ($j=0;$j<$NCoins;$j++) {
			if($Coin==$Coins[$j]['Coin']){
				$Coins[$j]['SpecifiedHashRate']= $Coins[$j]['SpecifiedHashRate'] + $SpecifiedHashRate;
				$Coins[$j]['ReportHashRate']= $Coins[$j]['ReportHashRate'] + $ReportHashRate;
				$Coins[$j]['AverageHashRate']= $Coins[$j]['AverageHashRate'] + $AverageHashRate;
				$Coins[$j]['EstimateRevenueCypto']= $Coins[$j]['EstimateRevenueCypto'] + $AverageHashRate*$Coins[$j]['EarningRate']*30;
				$Coins[$j]['EstimateRevenueUSD']= $Coins[$j]['EstimateRevenueCypto']*$Coins[$j]['CoinUSD'];
				//echo(var_dump($AverageHashRate*$Coins[$j]['EarningRate']));
				if(!in_array($Pool, $Coins[$j]['Pools'])){
					$Coins[$j]['Balance']= $Coins[$j]['Balance'] + $Balance;
					array_push($Coins[$j]['Pools'], $Pool);
				}

			}
		}
	}



	$TotalEarning = 0.0;
	for ($j=0;$j<$NCoins;$j++)
		$TotalEarning = $TotalEarning + $Coins[$j]['EstimateRevenueUSD'];

	//Bug!!!!!!!! TIME SHOULD BE THE LAST
	$UpdateTime = $ResultArray[0]['UpdateTime'];
	$PoolTime = $ResultArray[0]['PoolTime'];
	//Bug!!!!!!!! TIME SHOULD BE THE LAST
 
	echo "{";
	echo "\"MsgCode\": 0,";
	echo "\"Message\": \"Success\",";
	echo "\"CardAmounts\": $CardAmounts,";
	echo "\"Coins\": {";
	for ($i=0;$i<$NCoins;$i++) {
		echo "\"{$Coins[$i]['Coin']}\":";
		echo "{";
		echo "\"Coin\": \"{$Coins[$i]['Coin']}\",";
		echo "\"Balance\": {$Coins[$i]['Balance']},";
		echo "\"SpecifiedHashRate\": {$Coins[$i]['SpecifiedHashRate']},";
		echo "\"ReportHashRate\": {$Coins[$i]['ReportHashRate']},";
		echo "\"AverageHashRate\": {$Coins[$i]['AverageHashRate']},";
		echo "\"EstimateRevenueCypto\": {$Coins[$i]['EstimateRevenueCypto']},";
		echo "\"EstimateRevenueUSD\": {$Coins[$i]['EstimateRevenueUSD']}";
		echo $i<($NCoins-1)? "},":"}";
	}
	echo "},";	
	echo "\"EstimateRevenueUSD\": {$TotalEarning},";
	echo "\"PoolTime\": \"{$PoolTime}\",";
	echo "\"UpdateTime\": \"{$UpdateTime}\"";
	echo "}";
	 
	$db->close();
?>