<?php
include("config.php");
if (@$_GET['izdelie'])
{
	$id = $_GET['id'];
	$izdelie = $_GET['izdelie'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Наименование_изделия`='$izdelie' where ID_TAB='$id'"))
	echo 'success';
}
elseif ($_GET['klass_betona']){
        $id = $_GET['id'];
        $klass_betona = $_GET['klass_betona'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Класс_бетона`='$klass_betona' where ID_TAB='$id'"))
	echo 'success';
}
elseif ($_GET['prochnost']){
        $id = $_GET['id'];
        $prochnost = $_GET['prochnost'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Прочность_МПа`='$prochnost', `Прочность_проценты`='$prochnost'/`Требуемая_прочность_МПа`*'100' where ID_TAB='$id'") or $connection->query("update `$table` set `Прочность28`='$prochnost', `Прочность_28_проценты`='$prochnost'/`Требуемая_прочность_МПа`*'100' where ID_TAB='$id'"))
	echo 'success';
}
elseif ($_GET['tr_prochnost']){
        $id = $_GET['id'];
        $tr_prochnost = $_GET['tr_prochnost'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Требуемая_прочность_МПа`='$tr_prochnost', `Прочность_проценты`=`Прочность_МПа`/'$tr_prochnost'*'100' where ID_TAB='$id'") or $connection->query("update `$table` set `Требуемая_прочность_МПа`='$tr_prochnost', `Прочность_28_проценты`=`Прочность28`/'$tr_prochnost'*'100' where ID_TAB='$id'"))
	echo 'success';
}
elseif ($_GET['class']){
        $id = $_GET['id'];
        $class = $_GET['class'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Класс`='$class' where ID_TAB='$id'"))
	echo 'success';
}
elseif ($_GET['data']){
        $id = $_GET['id'];
        $data = $_GET['data'];
	$table = $_GET['table'];
	if($connection->query("update `$table` set `Дата`='$data' where ID_TAB='$id'"))
		echo $data;
	echo 'success';
}
?>
