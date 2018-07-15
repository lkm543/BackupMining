<?php
session_start();
echo ($_SESSION['Msg']."<br>");
unset($_SESSION['Msg']);
?>
If you have any problem in logging, please contact: kmlee@blockrane.com <br>

<form action="login.php" method="post">
　帳號：<input type="text" name="Account"><br>
　密碼：<input type="Password" name="Password"><br>
	<input type="submit" value="登入">
</form>