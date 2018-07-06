<?php
 // connect to the mysql database
require_once("./database.php");
require_once("./CoinType.php");


// retrieve the table and key from the path
//$table = $_GET['table'];
$table = 'GPU';
$id = $_GET['id'];
$Owner = $_GET['owner'];
$Hash = $_GET['hash'];
//echo(md5($id.$Owner.$id));
if(md5($id.$Owner.$id)==$Hash){
  switch ($id) {
    case 1:
      require_once('API_Dashboard.php');
      break;
    case 2:
      require_once('API_IndividualCard.php');
      break;
    default:
      break;
  }
}
else{
  echo "{";
  echo "\"MsgCode\": 2,";
  echo "\"Message\": \"Permission Denied\"";
  echo "}";
}
?>