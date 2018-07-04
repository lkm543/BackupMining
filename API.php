<?php
 // connect to the mysql database
require_once("./database.php");

$db = new db();
$db->DBConnect();
$result = $db->selectAll();

// retrieve the table and key from the path
//$table = $_GET['table'];
$table = 'GPU';
$id = $_GET['id'];
$address = $_GET['address'];
//$worker = $_GET['worker'];

$table = $db->escapeString($table);
$address = $db->escapeString($address);

$sql = "select GPU_Index,Worker,Address,Pool,Balance,SpecifiedHashRate,Reported,24Hrs,StartDate,UpdateTime,PoolTime from `$table` WHERE Address='$address'";
$result = $db->query($sql);

//echo(var_dump($sql));
//echo(var_dump($result));
 
// die if SQL statement failed
if (!$result) {
  http_response_code(404);
  die(mysqli_error());
}
echo "{\"workers\": [";
// print results, insert id or affected row count
for ($i=0;$i<mysqli_num_rows($result);$i++) {
  echo ($i>0?',':'').json_encode(mysqli_fetch_object($result));
}

echo "]}";
 
mysqli_close($link);
?>