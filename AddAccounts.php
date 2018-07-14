<?
session_start();
require_once("database.php");
require_once('DBsettings.php');

$db = new db();
$db->DBConnect();

$Account = $db->escapeString($_GET['Account']);
$Password = hash('sha256',$db->escapeString($_GET['Password']));
$User = $db->escapeString($_GET['User']);
$Permission = $db->escapeString($_GET['Permission']);

$sql = "Select * From Accounts where Account = '$Account'";
$result = $db->numRows($db->query($sql));

if ($_SESSION['Permission']==1){
	if($result==0){
		// Load settings from parent class
		$settings = DatabaseSettings::getSettings();
		// Get the main settings from the array we just loaded
		$host = $settings['dbhost'];
		$name = $settings['dbname'];
		$user = $settings['dbusername'];
		$pass = $settings['dbpassword'];
	    $conn = new PDO("mysql:host=$host;dbname=$name", $user, $pass);

	    // set the PDO error mode to exception
	    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql="INSERT INTO `Accounts`(`Account`, `Password`, `User`, `Permission`) VALUES ('$Account','$Password','$User','$Permission')";
		// use exec() because no results are returned
		$conn->exec($sql);	
		echo "Add Acounts Successfully!!";
	}
	else{
		echo "Account Exists!!";	
	}
}
else{
	echo "Permission Denied!!";
}

?>