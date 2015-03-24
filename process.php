<?php
include("config.php");
if($_GET['id'] and $_GET['izdelie'])
{
	$id = $_GET['id'];
	$izdelie = $_GET['izdelie'];
	if($connection->query("update `excel2mysql0_k2` set `Наименование_изделия`='$izdelie' where ID_TAB='$id'"))
	echo 'success';
}
?>
