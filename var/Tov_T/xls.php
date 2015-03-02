<h3>Товарный бетон "Тека"</h3>
<div align ="left">
<p><a href="index.php?viewInfo=7">Открыть таблицу</a></p>
<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<form enctype="multipart/form-data" action="index.php?viewInfo=9" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "var/Tov_T/file_tt.xlsx")) { // загружаемый файл всегда будет сохраняться под одним именем
    echo "Файл корректен и был успешно загружен.\n";
	echo "<h3>Информация о загруженном на сервер файле: </h3>";
	echo "<p><b>Оригинальное имя загруженного файла: ".@$_FILES['userfile']['name']."</b></p>";
	echo "<p><b>Mime-тип загруженного файла: ".@$_FILES['userfile']['type']."</b></p>";
	echo "<p><b>Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</b></p>";
	echo "<p><b>Временное имя файла: ".@$_FILES['userfile']['tmp_name']."</b></p>";
require_once "PHPExcel.php"; // Подключаем библиотеку
$PHPExcel_file = PHPExcel_IOFactory::load("var/Tov_T/file_tt.xlsx"); // Загружаем файл Excel
$PHPExcel_file->setActiveSheetIndex(0);// Преобразуем первый лист Excel в таблицу MySQL
echo excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_tt", 2) ? "Таблица EXCEL успешно преобразована в базу данных.\n" : "Таблица в файле не соответствует требуемому формату\n";
$connection->query("ALTER TABLE excel2mysql0_tt ADD ID_TAB INT(10) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ID_TAB`)");   // Добавляем в таблицу столбец ID_TAB с автоинкрементом
$result = $connection->query("SELECT `Дата`,`ID_TAB` FROM excel2mysql0_tt");
while($row = $result->fetch_array()){       //Преобразуем формат даты 
extract ($row);
$excel_timestamp = $row['Дата']-25568; // 1970-01-01 is day 25567. 
$php_timestamp = mktime(0,0,0,1,$excel_timestamp,1970); // No, really - this works! 
$mysql_timestamp = date('Y-m-d', $php_timestamp); // Or whatever the format is. 
$connection->query("UPDATE base.excel2mysql0_tt SET Дата = '$mysql_timestamp' WHERE excel2mysql0_tt.ID_TAB = '$ID_TAB'");//запись даты в базу
 }
$connection->query("ALTER TABLE `excel2mysql0_tt` CHANGE `Прочность28` `Прочность28` DECIMAL(10,1) NOT NULL"); // Добовить ноль росле запятой 
$connection->query("ALTER TABLE `excel2mysql0_tt` CHANGE `Требуемая_прочность_МПа` `Требуемая_прочность_МПа` DECIMAL(10,1) NOT NULL");
$connection->query("ALTER TABLE `excel2mysql0_tt` ADD `KOEF` INT(2) NOT NULL ");  //добавить столбец KOEF 
$connection->query("UPDATE `excel2mysql0_tt` SET `KOEF`=1");                      // записать единицу в KOEF   
$connection->query("DELETE FROM `base`.`excel2mysql0_tt` WHERE `excel2mysql0_tt`.`Класс` = ''");   //удалить строки с пустыми полями
$connection->query("ALTER TABLE `excel2mysql0_tt` CHANGE `Дата` `Дата` DATE NOT NULL");  //преобразуем текст в дату 
$connection->query( "CREATE TABLE excel2mysql0_tt2 LIKE excel2mysql0_t");
$connection->query("insert into `excel2mysql0_tt2` (`Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
 SELECT `Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF` FROM `excel2mysql0_tt`
LEFT JOIN `excel2mysql0_tt2`
using(`Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
WHERE `excel2mysql0_tt2`.`ID_TAB` IS NULL"); 
} else { echo "Файл не загружен.\n</br>"; } ?>
<form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="9"/>
<input type="submit">
</form>
<table align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px">
<tbody>
<tr><td>
<table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px"  >
  <tr>
   <td align="center" style="width:103px;">№</td>
   <td align="center" style="width:103px;">Дата <br/>изготовления</td>					
   <td align="center" style="width:103px;">Класс <br/>бетона</td>					
   <td align="center" style="width:103px;">Прочность <br/>7 суток, МПа</td>							
   <td align="center" style="width:103px;">Прочность <br/>28 суток, МПа</td>			
   <td align="center" style="width:103px;">Требуемая <br/>Прочность, МПа</td>  
   <td align="center" style="width:103px;">Прочность <br/>7 суток, %</td>	
   <td align="center" style="width:103px;">Прочность <br/>28 суток, %</td>	
   <td align="center" style="width:103px;">Прирост</td>
   <td align="center" style="width:103px;">Место <br/>отгрузки <br/>БС</td>
   <td align="center" style="width:103px;">Добавка</td>							
  </tr>
</table>
  </td></tr>
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px"  class="table_XLS">
<?php $nomer_str=0;
 $result = $connection->query("SELECT * FROM excel2mysql0_tt2 where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");// Запрос исходной таблицы с данными
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr>
<td align="center" style="width:103px; height:20px;"><?=++$nomer_str?></td>
<td><?=$row['Дата']?></td>
<td><?=$row['Класс']?></td>
<td><?=$row['Прочность7']?></td>
<td><?=$row['Прочность28']?></td>
<td><?=$row['Требуемая_прочность_МПа']?></td>
<td><?=$row['Прочность_7_проценты']?></td>
<td><?=$row['Прочность_28_проценты']?></td>
<td><?=$row['Прирост']?></td>
<td><?=$row['Место_отгрузки_БС']?></td>
<td><?=$row['Добавка']?></td>
</tr>
  <?php }?>
   </table>
 </td></tr>
</tbody>
</table>
</div>
