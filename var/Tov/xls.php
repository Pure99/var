<h3>Товарный бетон</h3>
<div align ="left">
<p><a href="index.php?viewInfo=4">Открыть таблицу</a></p>
	<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<form enctype="multipart/form-data" action="index.php?viewInfo=6" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "var/Tov/file.xlsx")) { // загружаемый файл всегда будет сохраняться под одним именем
    echo "Файл корректен и был успешно загружен.\n";
	echo "<h3>Информация о загруженном на сервер файле: </h3>";
	echo "<p><b>Оригинальное имя загруженного файла: ".@$_FILES['userfile']['name']."</b></p>";
	echo "<p><b>Mime-тип загруженного файла: ".@$_FILES['userfile']['type']."</b></p>";
	echo "<p><b>Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</b></p>";
	echo "<p><b>Временное имя файла: ".@$_FILES['userfile']['tmp_name']."</b></p>";
require_once "PHPExcel.php"; // Подключаем библиотеку
$PHPExcel_file = PHPExcel_IOFactory::load("var/Tov/file.xlsx"); // Загружаем файл Excel
$PHPExcel_file->setActiveSheetIndex(0);// Преобразуем первый лист Excel в таблицу MySQL
echo excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_t", 2) ? "Таблица EXCEL успешно преобразована в базу данных.\n" : "Таблица в файле не соответствует требуемому формату\n";
$connection->query("ALTER TABLE excel2mysql0_t ADD ID_TAB INT(10) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ID_TAB`)");   // Добавляем в таблицу столбец ID_TAB с автоинкрементом
$result = $connection->query("SELECT `Дата`,`ID_TAB` FROM excel2mysql0_t");
while($row = $result->fetch_array()){       //Преобразуем формат даты 
extract ($row);
$excel_timestamp = $row['Дата']-25568; // 1970-01-01 is day 25567. 
$php_timestamp = mktime(0,0,0,1,$excel_timestamp,1970); // No, really - this works! 
$mysql_timestamp = date('Y-m-d', $php_timestamp); // Or whatever the format is. 
$connection->query("UPDATE base.excel2mysql0_t SET Дата = '$mysql_timestamp' WHERE excel2mysql0_t.ID_TAB = '$ID_TAB'");//запись даты в базу
 }
$connection->query("ALTER TABLE `excel2mysql0_t` CHANGE `Прочность28` `Прочность28` DECIMAL(10,1) NOT NULL"); // Добовить ноль росле запятой 
$connection->query("ALTER TABLE `excel2mysql0_t` CHANGE `Требуемая_прочность_МПа` `Требуемая_прочность_МПа` DECIMAL(10,1) NOT NULL");
$connection->query("ALTER TABLE `excel2mysql0_t` ADD `KOEF` INT(2) NOT NULL ");  //добавить столбец KOEF 
$connection->query("UPDATE `excel2mysql0_t` SET `KOEF`=1");                      // записать единицу в KOEF   
$connection->query("DELETE FROM `base`.`excel2mysql0_t` WHERE `excel2mysql0_t`.`Класс` = ''");   //удалить строки с пустыми полями
$connection->query("ALTER TABLE `excel2mysql0_t` CHANGE `Дата` `Дата` DATE NOT NULL");  //преобразуем текст в дату ?>





<table align="center" border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" class="table_XLS"> 
   <tbody >
   <tr class="t_head" id="1">
   <td align="center">Дата <br/>изготовления</td>					
   <td align="center">Класс <br/>бетона</td>				
   <td align="center">БСЦ/РБУ</td>						
   <td align="center">Прочность <br/>7 суток, МПа</td>							
   <td align="center">Прочность <br/>28 суток, МПа</td>			
   <td align="center">Требуемая <br/>Прочность, МПа</td>  
   <td align="center">Прочность <br/>7 суток, %</td>	
   <td align="center">Прочность <br/>28 суток, %</td>	
   <td align="center">Прирост</td>
   <td align="center">Место <br/>отгрузки <br/>БС</td>
   <td align="center">Добавка</td>							
  </tr>
<?php
 $result = $connection->query("SELECT * FROM excel2mysql0_t ");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr >
<td ><input type="date" name="Date" onchange="alert (this.value);" value="<?php echo $row['Дата']?>" style="width:140px; height:20px; border:2px;" /></td>
<td><input type="text" name="Name" value="<?=$row['Класс']?>" style="width:130px; height:20px; border:2px"  /></td>
<td><input type="text" name="Class" value="<?=$row['БСЦ_РБУ']?>" style="width:50px; height:20px; border:2px; text-align:center;" /></td>
<td><input type="text" name="Strong_MPa" value="<?=$row['Прочность7']?>" style="width:120px; height:20px; border:2px;text-align:center"/></td>
<td><input type="text" name="Strong_MPa_Tr" value="<?=$row['Прочность28']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Strong_MPa_P" value="<?=$row['Требуемая_прочность_МПа']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_7_проценты']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_28_проценты']?>" style="width:110px; height:20px; border:2px; text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прирост']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Место_отгрузки_БС']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Добавка']?>" style="width:110px; height:20px; border:2px"   /></td>
</tr>
  <?php }?>
   </tbody>
  </table>
<?php
$connection->query( "CREATE TABLE excel2mysql0_t2 LIKE excel2mysql0_t");
$connection->query("insert into `excel2mysql0_t2` (`Дата`, `Класс`, `БСЦ_РБУ`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
 SELECT `Дата`, `Класс`, `БСЦ_РБУ`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF` FROM `excel2mysql0_t`
LEFT JOIN `excel2mysql0_t2`
using(`Дата`, `Класс`, `БСЦ_РБУ`, `Прочность7`, `Прочность28`, `Требуемая_прочность_МПа`, `Прочность_7_проценты`, `Прочность_28_проценты`, `Прирост`, `Место_отгрузки_БС`, `Добавка`, `KOEF`)
WHERE `excel2mysql0_t2`.`ID_TAB` IS NULL"); 
} else {  echo "Файл не загружен.\n</br>"; }
?>
 </div>