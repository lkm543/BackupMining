<?php
session_start();
require_once("database.php");
require_once('DBsettings.php');

//IP
if (!empty($_SERVER["HTTP_CLIENT_IP"])){
    $ip = $_SERVER["HTTP_CLIENT_IP"];
}elseif(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
}else{
    $ip = $_SERVER["REMOTE_ADDR"];
}


echo "YOUR IP IS:".$ip."</br>";

if ($_SESSION['Permission']==1){

  //計數器
  //變量$n是顯示位數
  $n=8;
  if (file_exists("counter.txt"))
  {
     //只讀方式打開type01_counter.txt文件 
    $counterfile=fopen("counter.txt","r");
     //讀取4位數字 
    $counter_num=fgets($counterfile,$n+1);
     //關閉文件
    fclose($counterfile);
  }
  else
  {
    $counter_num=1;
  }

  echo "<font color='purple'><b>"."截至".date('Y-m-d H:i:s')."總共有 ".$counter_num . "次登入</font></br>";

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

  echo "登入情形： </br>";
  $sql = "SELECT * FROM Log";
  //執行並查詢，查詢後只回傳一個查詢結果的物件，必須使用fetc、fetcAll...等方式取得資料
  $result = $conn->query($sql);

  echo "<table border='1'>";

  $i = 0;
  while($row = $result->fetch(PDO::FETCH_ASSOC))
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
  echo "</table>";
}
else{
    echo "訪客請移駕";
}

?>