<?php
session_start();
if (isset($_SESSION['zalogowany'])&&$_SESSION['level']<3)
{
echo '<div style="display:none;">';
$str = system('/usr/bin/free -h -t');
echo "</div>";
echo(substr($str,7));
}
?>