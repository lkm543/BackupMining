<?
session_start();
require_once("database.php");
require_once('DBsettings.php');
date_default_timezone_set("Asia/Taipei");

function get_client_ip(){
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


if($_SESSION['Login'] == 'True'){
	$Account = $_SESSION['Account'];
	$User = $_SESSION['User'];

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
	$UploadDate=date('Y-m-d H:i:s');
	$ips = get_client_ip();
    $sql="INSERT INTO Log (	Account , User , IP , Time , Event) VALUES ('$Account','$User','$ips','$UploadDate','Log out!')";
    // use exec() because no results are returned
    $conn->exec($sql);
	session_destroy();
	//$_SESSION['Msg'] = "Log out Successfully!!";
}
header('Location:https://digital-espacio.com/BackUpMining/');
?>