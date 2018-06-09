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
	$db = new db;
	$db -> DBConnect();
	$result = $db -> selectAll();

	echo "<table border='1' class=\"table table-striped table-dark\">";
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