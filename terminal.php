<?php

	session_start();
	
	if (!isset($_SESSION['zalogowany']))
	{
		header('Location: index.php?goto=terminal.php');
		exit();
	}
	if(isset($_POST['cmd'])&&$_SESSION['level']==0)
	{
	echo '<div style="display:none">';
	$str=system($_POST['cmd']);
	echo '</div>';
	}
?>
<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
	<title>RPi - Terminal</title>
	<script>
	function ajax2()
{
  var xhttp3 = new XMLHttpRequest();
  xhttp3.onreadystatechange = function() {
    if (xhttp3.readyState == 4)
     if (xhttp3.responseText!="") document.getElementById("cpu").innerHTML = "CPU:"+xhttp3.responseText;
  };
  xhttp3.open("GET", "cpu.php", true);
  xhttp3.send();
  var xhttp2 = new XMLHttpRequest();
  xhttp2.onreadystatechange = function() {
    if (xhttp2.readyState == 4)
     if (xhttp2.responseText!="") document.getElementById("temp").innerHTML = "Temp:"+xhttp2.responseText/1000+"&#x2103";
  };
  xhttp2.open("GET", "temp.php", true);
  xhttp2.send();
  setTimeout("ajax2()",1000);
  }
</script>
<style>
body
{
	background-color:gray;
}
#dane
{
	float:left;
	margin:2px;
	background-color:red;
	width:500px;
	height:147.5px;
}
#konto
{
	margin:2px;
	float:left;
	background-color:cyan;
	width:300px;
	height:147.5px;
}
#terminal
{
	margin:2px;
	clear:both;
	background-color:green;
	width:500px;
	height:147.5px;
}
</style>
</head>

<body onload="ajax2()">
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
		case 3: echo "User"; break;
		case 2: echo "User++"; break;
		case 1: echo "Admin"; break;
		case 0: echo "@ROOT@"; break;
	}
	if($_SESSION['level']<2)
	{
	echo '</div>';
	echo '<div id="terminal">';
	echo '<form method="POST" action="terminal.php">';
	echo '<h3>Terminal</h3>';
	echo '<input type="text" name="cmd">';
	echo '<input type="submit" value="Wyślij">';
	echo '</form>';
	echo 'Wyjście:';
	echo($str);
	echo '</div>';
	}
	else echo '</br><span style="color:red">Brak wystarczających uprawnień!</span>';
?>
</body>
</html>