<?php
include("connect.php");
if($_GET['id'] and $_GET['details'] and $_GET['data'])
{
	$id = $_GET['id'];
	$details = $_GET['details'];
	$data = $_GET['data'];
	if($connection->query("update information set details='$details', name='$data' where id='$id'"))
	echo 'success';
}
?>
