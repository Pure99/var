<?php
include("config.php");
if($_POST['id'] and $_POST['table']){
$id = $_POST['id'];
$table = $_POST['table'];
if($connection->query("DELETE FROM `$table` WHERE ((`ID_TAB` = '$id'))"))
	echo 'success';
}


