<?php
	
	//$worker = $_GET['worker'];

	$db = new db();
	$db->DBConnect();
	$result = $db->selectAll();

	$table = $db->escapeString($table);
	$address = $db->escapeString($address);

	$sql = "select GPU_Index,Worker,Address,Pool,Coin,SpecifiedHashRate,Reported,24Hrs,StartDate,UpdateTime,PoolTime from `GPU` WHERE Owner='$Owner'";
	$result = $db->query($sql);

	$CardAmounts = $db->numRows($result);
	while($line = $db -> fetchArray($result, MYSQLI_ASSOC)){
	    $ResultArray[] = $line;
	}

	//echo(var_dump($sql));
	//echo(var_dump($result));
	 
	// die if SQL statement failed
	if (!$result) {
	  http_response_code(404);
	  die(mysqli_error());
	}


	echo "{";
	echo "\"MsgCode\": 0,";
	echo "\"Message\": \"Success\",";
	echo "\"Workers\": [";
	for ($i=0;$i<$CardAmounts;$i++) {
		echo "{\"Worker\": \"{$ResultArray[$i]['Worker']}\",";
		echo "\"Coin\": \"{$ResultArray[$i]['Coin']}\",";
		echo "\"Pool\": \"{$ResultArray[$i]['Pool']}\",";
		echo "\"Address\": \"{$ResultArray[$i]['Address']}\",";
		echo "\"SpecifiedHashRate\": {$ResultArray[$i]['SpecifiedHashRate']},";
		echo "\"ReportHashRate\": {$ResultArray[$i]['Reported']},";
		echo "\"AverageHashRate\": {$ResultArray[$i]['24Hrs']},";
		echo "\"StartDate\": \"{$ResultArray[$i]['StartDate']}\",";
		echo "\"PoolTime\": \"{$ResultArray[$i]['PoolTime']}\",";
		echo "\"UpdateTime\": \"{$ResultArray[$i]['UpdateTime']}\"";
		echo $i<($CardAmounts-1)? "},":"}";
	}
	echo "]";	
	echo "}";
	 
	$db->close();
?>