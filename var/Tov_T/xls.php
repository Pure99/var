<h3>Товарный бетон "Тека"</h3>
<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<div align="left" class="pole jumbotron" style="position:relative"><form enctype="multipart/form-data" action="index.php?viewInfo=Тека_Ввести_данные" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" /><!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <!-- Название элемента input определяет имя в массиве $_FILES -->
 Отправить этот файл:<input name="userfile" type="file" style="display:inline"/><input type="submit" class="btn btn-primary" value="Отправить файл"/></form></div>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "var/Tov_T/file_tt.xlsx")) { // загружаемый файл всегда будет сохраняться под одним именем
echo "<div class='alert alert-success' role='alert'>Файл \"" .@$_FILES['userfile']['name']. "\" был успешно загружен. Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</div>";   
require_once "PHPExcel.php"; // Подключаем библиотеку
@$PHPExcel_file = PHPExcel_IOFactory::load("var/Tov_T/file_tt.xlsx"); // Загружаем файл Excel
$PHPExcel_file->setActiveSheetIndex(0);// Преобразуем первый лист Excel в таблицу MySQL
if ( excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_tt", 2)) {
echo "<div class='alert alert-success' role='alert'>Таблица EXCEL успешно преобразована в базу данных.</div>" ; 
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
$connection->query("update `excel2mysql0_tt` set `Класс`=replace(`Класс`, Left(`Класс`,1), 'В')");
$connection->query( "CREATE TABLE excel2mysql0_tt2 LIKE excel2mysql0_tt");
$connection->query("insert into `excel2mysql0_tt2` (`Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
 SELECT `Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF` FROM `excel2mysql0_tt`
LEFT JOIN `excel2mysql0_tt2`
using(`Дата`, `Класс`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
WHERE `excel2mysql0_tt2`.`ID_TAB` IS NULL"); 
} else { echo "<div class='alert alert-danger' role='alert'>Таблица в файле не соответствует требуемому формату.</div>";}
} ?>
<div style="float:left; width:177px; height:177px;"><div class="pole jumbotron"><form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="Тека_Ввести_данные"/>
<br><input type="submit" class="btn btn-primary">
</form>
</div></div>
<div class="print"><table class="table-autostripe table-rowshade-alternate table-autosort table-autofilter table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount" align="center" border="1px" cellpadding="0px" cellspacing="0px" id="table1">
<thead>
   <td class="table-filterable table-sortable:numeric" align="center" style="width:50px; height:20px;">№<br></td>
   <td class="table-filterable table-sortable:default" align="center" style="width:94px; height:20px;">Дата <br/>изготовления</td>					
   <td class="table-filterable table-sortable:default" align="center" style="width:122px; height:20px;">Класс <br/>бетона</td>					
   <td class="table-filterable table-sortable:numeric" align="center" style="width:90px; height:20px;">Прочность <br/>7 суток, МПа</td>							
   <td class="table-filterable table-sortable:numeric" align="center" style="width:95px; height:20px;">Прочность <br/>28 суток, МПа</td>			
   <td class="table-filterable table-sortable:numeric" align="center" style="width:110px; height:20px;">Требуемая <br/>Прочность, МПа</td>  
   <td class="table-filterable table-sortable:numeric" align="center" style="width:80px; height:20px;">Прочность <br/>7 суток, %</td>	
   <td class="table-filterable table-sortable:numeric" align="center" style="width:80px; height:20px;">Прочность <br/>28 суток, %</td>	
   <td class="table-filterable table-sortable:numeric" align="center" style="width:80px; height:20px;">Прирост<br></td>
   <td class="table-filterable table-sortable:default" align="center" style="width:80px; height:20px;">Место <br/>отгрузки БС</td>
   <td class="table-filterable table-sortable:default" align="center" style="width:80px; height:20px;">Добавка<br></td>							
  </thead>
<?php $nomer_str=0;
 $result = $connection->query("SELECT * FROM excel2mysql0_tt2 where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");// Запрос исходной таблицы с данными
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr id="<?=$ID_TAB?>">
<td align="center"><?=++$nomer_str?></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_tt2 data" data-placement="right" data-title="Дата"><?=$Дата?></span></td>
<td align="left"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_tt2 class" data-type="text" data-placement="right" data-title="Класс бетона"><?=$row['Класс']?></span></td>
<td align="center"><?=str_replace('.',',',$row['Прочность7'])?></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_tt2 prochnost" data-type="text" data-placement="right" data-title="Прочность 28 суток"><?=str_replace('.',',',$row['Прочность28'])?></span></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_tt2 tr_prochnost" data-type="text" data-placement="right" data-title="Требуемая прочность"><?=str_replace('.',',',$row['Требуемая_прочность_МПа'])?></span></td>
<td align="center"><?=$row['Прочность_7_проценты']?></td>
<td align="center" <?if ($row['Прочность_28_проценты']<100) echo "style='color:red'";?>><?=$row['Прочность_28_проценты']?></td>
<td align="center"><?=$row['Прирост']?></td>
<td align="center"><?=$row['Место_отгрузки_БС']?></td>
<td align="center"><?=$row['Добавка']?></td>
<td width="21px"  id="<?=$ID_TAB?>" class="delete" align="center" valign="middle" title="Удалить строку"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>
</tr>
  <?php }?>
<tfoot><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td><td> </td>
<td width="21px" bgcolor="#419641" id="excel2mysql0_tt2" class="add" align="center" valign="middle" title="Добавить новую строку"><span align="center" class="glyphicon glyphicon-plus" aria-hidden="true"></span></td>
</tfoot> </table></div>
