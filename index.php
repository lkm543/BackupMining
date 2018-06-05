<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>內部測試</title>
		<link href="css/style.css" rel="stylesheet">
</head>
<body>

<?
	include_once("database.php");
	$db = new db;
	$db -> DBConnect();
	$result = $db -> selectAll();

	echo "<table border='1'>";
	$i = 0;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc())
		{
		  if ($i == 0) {
		    $i++;
		    echo "<tr>";
		    foreach ($row as $key => $value) {
		      echo "<th>" . $key . "</th>";
		    }
		    echo "</tr>";
		  }
		  echo "<tr>";
		  foreach ($row as $value) {
		    echo "<td>" . $value . "</td>";
		  }
		  echo "</tr>";
		}
	} else {
	    echo "0 results";
	}
	echo "</table>";


	$result = $db -> selectPool('nano');
	echo "<br><table border='1'>";
	$i = 0;
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc())
		{
		  if ($i == 0) {
		    $i++;
		    echo "<tr>";
		    foreach ($row as $key => $value) {
		      echo "<th>" . $key . "</th>";
		    }
		    echo "</tr>";
		  }
		  echo "<tr>";
		  foreach ($row as $value) {
		    echo "<td>" . $value . "</td>";
		  }
		  echo "</tr>";
		}
	} else {
	    echo "0 results";
	}
	echo "</table>";
?>
		
</body>
</html>