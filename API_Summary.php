<?php
	
	$Price2Y = 50000;
	$Price4Y = 70000;
	$HashRate = 39;

	if (!empty($_GET)) {
		if ($_GET["Price2Y"]!=NULL) {
			$Price2Y = $_GET["Price2Y"];
		}
		if ($_GET["Price4Y"]!=NULL) {
			$Price4Y = $_GET["Price4Y"];
		}
		if ($_GET["HashRate"]!=NULL) {
			$HashRate = $_GET["HashRate"];
		}
	}

	$db = new db();
	$db->DBConnect();
	$sql2 = "SELECT * FROM `Analysis` ORDER BY UpdateTime DESC LIMIT 1";
	$result2 = $db->query($sql2);
	//echo(var_dump($result2));
	$AnalysisArray = array();
	while($line = $db -> fetchArray($result2, MYSQLI_ASSOC)){
		$AnalysisArray[] = $line;
	}

	$EarningETH_F2Pool = floatval($AnalysisArray[0]['EarningETH']);
	$EarningETH_EthFan = floatval($AnalysisArray[0]['EarningETH_EthFan']);
	$EarningETH = max(floatval($AnalysisArray[0]['EarningETH']),floatval($AnalysisArray[0]['EarningETH_EthFan']));
	$ETHUSD = floatval($AnalysisArray[0]['ETHUSD']);
	$USDTWD = floatval($AnalysisArray[0]['USDTWD']);

	$RevenueTWD2Y = floatval($AnalysisArray[0]['RevenueTWDY'])*2*$HashRate;
	$RevenueTWD4Y = floatval($AnalysisArray[0]['RevenueTWDY'])*4*$HashRate;
 
	$timeNow = date("Y-m-d H:i:s");



	//$worker = $_GET['worker'];

	$db = new db();
	$db->DBConnect();
	$result = $db->selectAll();

	$table = $db->escapeString($table);
	$address = $db->escapeString($address);

	$sql = "select GPU_Index,SpecifiedHashRate,Reported,24Hrs,Status from `GPU` WHERE 1";
	$result = $db->query($sql);
	$CardAmounts = floatval($db->numRows($result));

	$resultArray = array();
	while($line = $db -> fetchArray($result, MYSQLI_ASSOC)){
		$resultArray[] = $line;
	}
	
	$TotalSHR = 0;
	$TotalRHR = 0;
	$TotalLHR = 0;
	$FCards = 0;
	$SRCards = 0;
	$SLCards = 0;
	$ShutDownCards = 0;
	$APIError = 0;

	foreach ($resultArray as $item) {
	    $TotalSHR += $item['SpecifiedHashRate'];
	    $TotalRHR += $item['Reported'];
	    $TotalLHR += $item['24Hrs'];
	    switch ($item['Status']) {
	    	case 'Fine':
	    		$FCards++;
	    		break;
	    	case 'Slow Report':
	    		$SRCards++;
	    		break;
	    	case 'Slow LongTerm':
	    		$SLCards++;
	    		break;
	    	case 'Shut Down':
	    		$ShutDownCards++;
	    		break;
	    	case 'API Error':
	    		$APIError++;
	    		break;
	    }
	}

	echo "{";
	echo "\"MsgCode\": 0,";
	echo "\"Message\": \"Success\",";
	echo "\"EarningETH_F2Pool\": \"".number_format($EarningETH_F2Pool,8, ".", "")."\",";
	echo "\"EarningETH_EthFan\": \"".number_format($EarningETH_EthFan,8, ".", "")."\",";
	echo "\"ETHUSD\": ".number_format($ETHUSD,3, ".", "").",";
	echo "\"USDTWD\": ".number_format($USDTWD,3, ".", "").",";
	echo "\"Estimate2Y\": {";
		echo "\"RevenueTWD2Y\": ".number_format($RevenueTWD2Y,3, ".", "").",";
		echo "\"ROI(%)\": ".number_format($RevenueTWD2Y/$Price2Y*100,2, ".", "").",";
		echo "\"BEP(ETHUSD)\": ".number_format(($Price2Y/$RevenueTWD2Y*$ETHUSD),2, ".", "");
	echo "},";
	echo "\"Estimate4Y\": {";
		echo "\"RevenueTWD4Y\": ".number_format($RevenueTWD4Y,3, ".", "").",";
		echo "\"ROI(%)\": ".number_format($RevenueTWD4Y/$Price4Y*100,2, ".", "").",";
		echo "\"BEP(ETHUSD)\": ".number_format(($Price4Y/$RevenueTWD4Y*$ETHUSD),2, ".", "");
	echo "},";

	echo "\"TotalSpecifiedHashRate\": ".number_format($TotalSHR,3, ".", "").",";
	echo "\"TotalReportHashRate\": ".number_format($TotalRHR,3, ".", "").",";
	echo "\"TotalLongTermHashRate\": ".number_format($TotalLHR,3, ".", "").",";
	echo "\"CardAmounts\": ".$CardAmounts.",";
	echo "\"FineCards\": ".$FCards.",";
	echo "\"SlowReportCards\": ".$SRCards.",";
	echo "\"SlowLongtermCards\": ".$SLCards.",";
	echo "\"ShutDownCards\": ".$ShutDownCards.",";
	echo "\"APIErrorCards\": ".$APIError.",";

	echo "\"UpdateTime\": \"{$AnalysisArray[0]['UpdateTime']}\",";
	echo "\"TimeNow\": \"{$timeNow}\"";
	echo "}";
	 
	$db->close();
?>