<?php
include("config.php");
if($_POST['table']){
$table = $_POST['table'];
if($connection->query("INSERT INTO `$table` (`Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF`)
VALUES (now(), '', '', '', '', '', '', '');"))
$result=$connection->query("select * from `$table` ORDER BY ID_TAB DESC LIMIT 1;");
$row = $result->fetch_array();
	echo "<tr id=\"".$row['ID_TAB']."\">
<td align=\"center\"></td><td align=\"center\">".$row['Дата']."</td>
<td><span id=\"".$row['ID_TAB']."\" class=\"".$table." izdelie\" data-type=\"text\" data-placement=\"right\" data-title=\"Наименование изделия\">".$row['Наименование_изделия']."</span></td>
<td align=\"center\"><span id=\"".$row['ID_TAB']."\" class=\"".$table." klass_betona\" data-type=\"text\" data-placement=\"right\" data-title=\"Класс бетона\">".$row['Класс_бетона']."</span></td>
<td align=\"center\"><span id=\"".$row['ID_TAB']."\" class=\"".$table." prochnost\" data-type=\"text\" data-placement=\"right\" data-title=\"Прочность, МПа\">".$row['Прочность_МПа']."</span></td>
<td align=\"center\"><span id=\"".$row['ID_TAB']."\" class=\"".$table." tr_prochnost\" data-type=\"text\" data-placement=\"right\" data-title=\"Требуемая прочность\">".$row['Требуемая_прочность_МПа']."</span></td>
<td align=\"center\" class=\"proc\">".$row['Прочность_проценты']."</td>
<td align=\"center\">".$row['Добавка']."</td>
<td width=\"21px\" id=\"".$row['ID_TAB']."\" class=\"delete\" align=\"center\" valign=\"middle\"><span class=\"glyphicon glyphicon-remove\" aria-hidden=\"true\"></span></td></tr>";
}

