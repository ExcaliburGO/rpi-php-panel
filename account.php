<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php?goto=account.php');
		exit();
	}
	
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>RPi - Konto</title>
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
#reszta
{
	margin:2px;
	clear:both;
	background-color:white;
	width:804px;
}
</style>
</head>

<body onload="ajax()">
<div id="dane">
<a href="gra.php"><img src="raspberry-pi-logo.png"/></a>
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
		case 3: $echos = "User"; break;
		case 2: $echos = "User++"; break;
		case 1: $echos = "Admin"; break;
		case 0: $echos = "@ROOT@"; break;
	}
	
	echo $echos.'</div><div id="reszta">';
	echo "Nazwa użytkownika:".$_SESSION['user'];
	echo "</br>Adres E-mail:".$_SESSION['email'];
	echo ' <a href="email.php">zmień</a></br>Poziom uprawnień:'.$echos;
	echo '</br><progress value="'.(4-$_SESSION['level']).'" max="4"></progress>';
?>
</body>
</html>