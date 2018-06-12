<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>內部測試</title>

	<link href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body>

<?
	include_once("database.php");
	include_once("nano.php");

	//New Class?
	$Status = array(
	    1  => "Fine",
	    2 => "Slow",
	    3 => "Shut Down",
	    4 => "API Error",
	);

	$db = new db();
	$nano = new nano();

	$db -> DBConnect();
	$result = $db -> selectAll();

	//result to array
	$ResultArray = array();
	while($line = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    $ResultArray[] = $line;
	}

	$i = 0;
	while($i<$result->num_rows)
	{
		if($ResultArray[$i]["Pool"]=="nano"){
			$nano->reset();
			$nano->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");

			$nano->getDataFromPool();
			$ResultArray[$i]["Status"] = 0;
			if(!$nano->ErrorFlag){
				$ResultArray[$i]["Reported"] = $nano->ReportedHashRate;
				$ResultArray[$i]["PoolHashRate"] = $nano->HashRate_LongTerm;
				if($ResultArray[$i]["Reported"]==0){
				//0 Y 1 Warn 2 Error
					$ResultArray[$i]["Status"] = 3;
				}				
				elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
					$ResultArray[$i]["Status"] = 2;
				}
				else{
					$ResultArray[$i]["Status"] = 1;
				}
			}
			else{
				//echo("Error!!");
				$ResultArray[$i]["Status"] = 4;
			}
		}
		/*
		elseif($ResultArray[$i]["Pool"]=="eth-tw"){
			$nano->reset();
			$nano->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");

			$nano->getDataFromPool();
			$ResultArray[$i]["Status"] = 0;
			if(!$nano->ErrorFlag){
				$ResultArray[$i]["Reported"] = $nano->ReportedHashRate;
				$ResultArray[$i]["PoolHashRate"] = $nano->HashRate_LongTerm;
				if($ResultArray[$i]["Reported"]==0){
				//0 Y 1 Warn 2 Error
					$ResultArray[$i]["Status"] = 3;
				}				
				elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
					$ResultArray[$i]["Status"] = 2;
				}
				else{
					$ResultArray[$i]["Status"] = 1;
				}
			}
			else{
				//echo("Error!!");
				$ResultArray[$i]["Status"] = 4;
			}
		}
		elseif($ResultArray[$i]["Pool"]=="f2pool"){
			$nano->reset();
			$nano->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");

			$nano->getDataFromPool();
			$ResultArray[$i]["Status"] = 0;
			if(!$nano->ErrorFlag){
				$ResultArray[$i]["Reported"] = $nano->ReportedHashRate;
				$ResultArray[$i]["PoolHashRate"] = $nano->HashRate_LongTerm;
				if($ResultArray[$i]["Reported"]==0){
				//0 Y 1 Warn 2 Error
					$ResultArray[$i]["Status"] = 3;
				}				
				elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
					$ResultArray[$i]["Status"] = 2;
				}
				else{
					$ResultArray[$i]["Status"] = 1;
				}
			}
			else{
				//echo("Error!!");
				$ResultArray[$i]["Status"] = 4;
			}
		}
		elseif($ResultArray[$i]["Pool"]=="uul"){
			$nano->reset();
			$nano->setBasicData($ResultArray[$i]["Address"],$ResultArray[$i]["Worker"],"ETH");

			$nano->getDataFromPool();
			$ResultArray[$i]["Status"] = 0;
			if(!$nano->ErrorFlag){
				$ResultArray[$i]["Reported"] = $nano->ReportedHashRate;
				$ResultArray[$i]["PoolHashRate"] = $nano->HashRate_LongTerm;
				if($ResultArray[$i]["Reported"]==0){
				//0 Y 1 Warn 2 Error
					$ResultArray[$i]["Status"] = 3;
				}				
				elseif($ResultArray[$i]["Reported"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]||$ResultArray[$i]["PoolHashRate"]<0.9*$ResultArray[$i]["SpecifiedHashRate"]){
					$ResultArray[$i]["Status"] = 2;
				}
				else{
					$ResultArray[$i]["Status"] = 1;
				}
			}
			else{
				//echo("Error!!");
				$ResultArray[$i]["Status"] = 4;
			}
		}
		*/
		$i++;
	}

	echo "<table border='1' class=\"table table-striped\">";
	$i = 0;
	if ($result->num_rows > 0) {
		while($i<$result->num_rows)
		{
			if ($i == 0) {
				echo "<tr>";
				echo "<th>Woker</th>";
				echo "<th>Address</th>";
				echo "<th>Pool</th>";
				echo "<th>Comment</th>";
				echo "<th>Owner</th>";
				echo "<th>Specified</th>";
				echo "<th>Reported</th>";
				echo "<th>Longterm</th>";
				echo "<th>Rig</th>";
				echo "<th>Card</th>";
				echo "<th>Status</th>";
				echo "</tr>";
			}
			else{
				echo "<tr";
				switch ($ResultArray[$i]["Status"]) {
					case 1:
						echo(" class=\"table-success\"");
						break;
					case 2:
						echo(" class=\"table-warning\"");
						break;
					case 3:
						echo(" class=\"table-danger\"");
						break;
					case 4:
						echo(" class=\"table-secondary\"");
						break;
				}
				echo ">";
				echo "<td>".$ResultArray[$i]["Worker"]."</td>";
				echo "<td>".$ResultArray[$i]["Address"]."</td>";
				echo "<td>".$ResultArray[$i]["Pool"]."</td>";
				echo "<td>".$ResultArray[$i]["Comment"]."</td>";
				echo "<td>".$ResultArray[$i]["Owner"]."</td>";
				echo "<td>".$ResultArray[$i]["SpecifiedHashRate"]."</td>";
				echo "<td>".$ResultArray[$i]["Reported"]."</td>";
				echo "<td>".$ResultArray[$i]["PoolHashRate"]."</td>";
				echo "<td>".$ResultArray[$i]["Rig"]."</td>";
				echo "<td>".$ResultArray[$i]["Card"]."</td>";
				echo "<td>".$Status[$ResultArray[$i]["Status"]]."</td>";
				echo "</tr>";
			}
			$i++;
		}
	} else {
	    echo "0 results";
	}
	echo "</table>";
?>
		
</body>
</html>