<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php?goto=adduser.php');
		exit();
	}
	if(isset($_POST['user'])&&isset($_POST['pass'])&&isset($_POST['email'])&&isset($_POST['level'])&&$_SESSION['level']<2)
	{
		require_once "connect.php";

	$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		mysqli_query($polaczenie, "SET CHARSET utf8");
		mysqli_query($polaczenie, "SET NAMES 'utf8' COLLATE 'utf8_polish_ci'");
		$user = $_POST['user'];
		$user = htmlentities($user, ENT_QUOTES, "UTF-8");
		if(mysqli_num_rows(@$polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",mysqli_real_escape_string($polaczenie,$user))))==0)
		{
		$pass = $_POST['pass'];
		if(strlen($pass)>7)
		{
		$email = $_POST['email'];
		$levels = $_POST['level'];
		$pass = md5($pass);
		$email = htmlentities($email, ENT_QUOTES, "UTF-8");
		switch($levels)
		{
			case 1: $level=1; break;
			case 2: $level=2; break;
			case 3: $level=3; break;
			default: $level=3;
		}
		if ($rezultat = @$polaczenie->query(
		sprintf("INSERT INTO uzytkownicy VALUES (NULL, '%s', '%s', '%s', '%s')",
		mysqli_real_escape_string($polaczenie,$user),
		mysqli_real_escape_string($polaczenie,$pass),
		$level,
		mysqli_real_escape_string($polaczenie,$email))))
		{
				header('Location: gra.php');
				
		}			
		$polaczenie->close();
		exit();
	}
	}
	
	}
	
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>RPi</title>
	<script>
function ajax()
{
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4)
     if (xhttp.responseText!="") document.getElementById("cpu").innerHTML = "CPU:"+xhttp.responseText;
  };
  xhttp.open("GET", "cpu.php", true);
  xhttp.send();
  var xhttp2 = new XMLHttpRequest();
  xhttp2.onreadystatechange = function() {
    if (xhttp2.readyState == 4)
     if (xhttp2.responseText!="") document.getElementById("temp").innerHTML = "Temp:"+xhttp2.responseText/1000+"&#x2103";
  };
  xhttp2.open("GET", "temp.php", true);
  xhttp2.send();
  setTimeout("ajax()",1000);
  }
</script>
<style>
body
{
	background-color:gray;
}
#konto
{
	margin:2px;
	float:left;
	background-color:cyan;
	width:300px;
	height:147.5px;
}
#dane
{
	margin:2px;
	float:left;
	background-color:red;
	width:500px;
	height:147.5px;
}
#user
{
	margin:2px;
	clear:both;
	background-color:green;
	width:804px;
	height:160px;
}
</style>
</head>

<body onload="ajax()">
<div id="dane">
<a href="gra.php"><img src="raspberry-pi-logo.png"></a>
<?php
if($_SESSION['level']==0) echo '<a href="terminal.php"><img src="terminal.png" height="100"/></a>';
if($_SESSION['level']<3) echo '<a href="morepi.php"><img src="info.png" height="100"/></a>';
if($_SESSION['level']<2) echo '<a href="adduser.php"><img src="list_add_user.png" height="100"/></a>';
if($_SESSION['level']<2) echo '<a href="phpmyadmin"><img src="phpmyadmin-logo.png" height="100"/></a>';
?>
<div id="cpu">CPU:</div>
<div id="temp">Temp:</div>
</div>
<div id="konto">
<a href="account.php"><img src="subAccountImg.gif" height="83"/></a>
<a href="mailview.php"><img src="mail.png" height="83"/></a>
</br>
<?php
	echo "Witaj ".$_SESSION['user'].'! [ <a href="logout.php">Wyloguj się!</a> ]</br>';
	echo "E-mail: ".$_SESSION['email'];
	echo "<br />Uprawnienia:";
	switch($_SESSION['level'])
	{
		case 3: echo "User"; break;
		case 2: echo "User++"; break;
		case 1: echo "Admin"; break;
		case 0: echo "@ROOT@"; break;
	}
	if($_SESSION['level']<2)
	{
	echo '</div><div id="user">';
	echo '<h3>Dodaj nowego użytkownika:</h3>';
	echo '<form action="adduser.php" method="POST">';
	echo 'Nazwa użytkownika:<input type="text" name="user">';
	echo '</br>Hasło:<input type="password" name="pass">';
	echo '</br>E-mail:<input type="text" name="email">';
	echo '</br>Uprawnienia:<select name="level">';
	echo '<option value="3">User</option>';
	echo '<option value="2">User++</option>';
	echo '<option value="1">Admin</option>';
	echo '</select>';
	echo '</br><input type="submit" value="Dodaj nowego użytkownika">';
	echo '</form>';
	echo '</div>';
	}
	else echo '</br><span style="color:red">Brak wystarczających uprawnień!</span>';
	if(isset($_POST['pass']))
	{
		if(strlen($_POST['pass'])<8) echo '</br><span style="color:red">Hasło musi zawierać minimum 8 znaków!</span>';
	if(mysqli_num_rows(@$polaczenie->query(sprintf("SELECT * FROM uzytkownicy WHERE user='%s'",mysqli_real_escape_string($polaczenie,$user))))!=0) echo '</br><span style="color:red">Taki użytkownik już istnieje!</span>';
	}
?>
</body>
</html>