<?php
session_start();
if (isset($_SESSION['zalogowany'])) $str = system('/var/www/proc.sh');
?>