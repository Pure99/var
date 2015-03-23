<?php
include("config.php");
if($_GET['id'] and $_GET['data'])
{
	$id = $_GET['id'];
	$data = $_GET['data'];
	if($connection->query("update `excel2mysql0_k2` set `Наименование_изделия`='$data' where ID_TAB='$id'"))
	echo 'success';
}
?>
