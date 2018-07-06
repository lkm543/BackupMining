<?php
	
	//$worker = $_GET['worker'];

	$db = new db();
	$db->DBConnect();
	$result = $db->selectAll();

	$table = $db->escapeString($table);
	$address = $db->escapeString($address);

	$sql = "select GPU_Index,Worker,Address,Pool,Balance,SpecifiedHashRate,Reported,24Hrs,StartDate,UpdateTime,PoolTime from `$table` WHERE Owner='$Owner'";
	$result = $db->query($sql);

	//echo(var_dump($sql));
	//echo(var_dump($result));
	 
	// die if SQL statement failed
	if (!$result) {
	  http_response_code(404);
	  die(mysqli_error());
	}
	echo "{";
	//echo "\"owner\":,"
	echo "\"workers\": [";
	// print results, insert id or affected row count
	for ($i=0;$i<mysqli_num_rows($result);$i++) {
	  echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
	}

	echo "]}";
	 
	$db->close();
?>