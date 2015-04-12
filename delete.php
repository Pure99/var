<?php
include("config.php");
if($_POST['id']){
$id = $_POST['id'];
if($connection->query("DELETE FROM `excel2mysql0_k2` WHERE ((`ID_TAB` = '$id'))"))
	echo 'success';
}


