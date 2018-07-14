<?php 
session_start();
if ($_SESSION['Login']=='True'){
    date_default_timezone_set("Asia/Taipei");
	include_once("database.php");

	$db = new db();
	$db -> DBConnect();
	$result = $db -> selectAll();

	//result to array
	$ResultArray = array();
	while($line = $db -> fetchArray($result, MYSQLI_ASSOC)){
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
				echo "<th>PoolTime</th>";
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
			echo "<td>".$ResultArray[$i]["PoolTime"]."</td>";
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
}
?>
