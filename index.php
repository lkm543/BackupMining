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
	date_default_timezone_set("Asia/Taipei");
	
	include_once("database.php");

	$db = new db();
	$db -> DBConnect();
	$result = $db -> selectAll();

	//result to array
	$ResultArray = array();
	while($line = mysqli_fetch_array($result, MYSQLI_ASSOC)){
	    $ResultArray[] = $line;
	}

	echo "<table border='1' class=\"table table-striped\">";
	$i = 0;
	if ($result->num_rows > 0) {
		while($i<$result->num_rows)
		{
			if ($i == 0) {
				echo "<tr>";
				echo "<th>Woker</th>";
				echo "<th>Pool</th>";
				echo "<th>Specified</th>";
				echo "<th>Reported</th>";
				echo "<th>24Hrs</th>";
				echo "<th>Rig</th>";
				echo "<th>Card</th>";
				echo "<th>Status</th>";
				echo "<th>UpdateTime</th>";
				echo "<th>Owner</th>";
				echo "<th>Address</th>";
				echo "<th>Comment</th>";
				echo "</tr>";
			}
			echo "<tr";
			switch ($ResultArray[$i]["Status"]) {
				case "Fine":
					echo(" class=\"table-success\"");
					break;
				case "Slow LongTerm":
					echo(" class=\"table-warning\"");
					break;
				case "Slow Report":
					echo(" class=\"table-danger\"");
					break;
				case "Shut Down":
					echo(" class=\"table-danger\"");
					break;
				case "API Error":
					echo(" class=\"table-secondary\"");
					break;
			}
			echo ">";
			echo "<td>".$ResultArray[$i]["Worker"]."</td>";
			echo "<td>".$ResultArray[$i]["Pool"]."</td>";
			echo "<td>".$ResultArray[$i]["SpecifiedHashRate"]."</td>";
			echo "<td>".number_format($ResultArray[$i]["Reported"],2)."</td>";
			echo "<td>".number_format($ResultArray[$i]["24Hrs"],2)."</td>";
			echo "<td>".$ResultArray[$i]["Rig"]."</td>";
			echo "<td>".$ResultArray[$i]["Card"]."</td>";
			echo "<td>".$ResultArray[$i]["Status"]."</td>";
			echo "<td>".$ResultArray[$i]["UpdateTime"]."</td>";
			echo "<td>".$ResultArray[$i]["Owner"]."</td>";
			echo "<td>".$ResultArray[$i]["Address"]."</td>";
			echo "<td>".$ResultArray[$i]["Comment"]."</td>";
			echo "</tr>";
			$i++;
		}
	} else {
	    echo "0 results";
	}
	echo "</table>";
?>
		
</body>
</html>