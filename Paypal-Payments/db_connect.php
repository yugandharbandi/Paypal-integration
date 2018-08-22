<?php
$host='localhost';
$usrname='root';
$password_db='';
$db_name='scout database';
mysql_connect($host,$usrname,$password_db) or die("could not connect");
mysql_select_db($db_name) or die("could not connect");
// echo 'connected to database';

?>
