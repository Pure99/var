  <h3>Конструкционный бетон</h3>
<div align ="left">
<p><a href="index.php?viewInfo=1">Открыть таблицу</a></p>
	<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<form enctype="multipart/form-data" action="index.php?viewInfo=3" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" /> 
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit"  value="Send File" />
</form>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "var/Konst/file.xlsx")) {   // загружаемый файл всегда будет сохраняться под одним именем
    echo "Файл корректен и был успешно загружен.\n";
	echo "<h3>Информация о загруженном на сервер файле: </h3>";
	echo "<p><b>Оригинальное имя загруженного файла: ".@$_FILES['userfile']['name']."</b></p>";
	echo "<p><b>Mime-тип загруженного файла: ".@$_FILES['userfile']['type']."</b></p>";
	echo "<p><b>Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</b></p>";
	echo "<p><b>Временное имя файла: ".@$_FILES['userfile']['tmp_name']."</b></p>";
require_once "PHPExcel.php";// Подключаем библиотеку
$PHPExcel_file = PHPExcel_IOFactory::load("var/Konst/file.xlsx");// Загружаем файл Excel
$PHPExcel_file->setActiveSheetIndex(0);// Преобразуем первый лист Excel в таблицу MySQL
echo excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_k", 2) ? "Таблица EXCEL успешно преобразована в базу данных.\n" : "Таблица в файле не соответствует требуемому формату.\n";
$connection->query("ALTER TABLE excel2mysql0_k ADD ID_TAB INT(10) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ID_TAB`)");   // Добавляем в таблицу столбец ID_TAB с автоинкрементом
$result = $connection->query("SELECT `Дата`,`ID_TAB` FROM excel2mysql0_k");
while($row = $result->fetch_array()){       //Преобразуем формат даты
extract ($row);
$excel_timestamp = $row['Дата']-25568; // 1970-01-01 is day 25567.
$php_timestamp = mktime(0,0,0,1,$excel_timestamp,1970); // No, really - this works! 
$mysql_timestamp = date('Y-m-d', $php_timestamp); // Or whatever the format is. 
$connection->query("UPDATE base.excel2mysql0_k SET Дата = '$mysql_timestamp' WHERE excel2mysql0_k.ID_TAB = '$ID_TAB'");//запись даты в базу
 }
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Прочность_МПа` `Прочность_МПа` DECIMAL(10,1) NOT NULL"); // Добовить ноль росле запятой 
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Требуемая_прочность_МПа` `Требуемая_прочность_МПа` DECIMAL(10,1) NOT NULL");
$connection->query("ALTER TABLE `excel2mysql0_k` ADD `KOEF` INT(2) NOT NULL ");  //добавить столбец KOEF 
$connection->query("UPDATE `excel2mysql0_k` SET `KOEF`=1");                      // записать единицу в KOEF   
$connection->query("DELETE FROM `base`.`excel2mysql0_k` WHERE `excel2mysql0_k`.`Наименование_изделия` = ''");   //удалить строки с пустыми полями
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Класс_бетона` `Класс_бетона` DECIMAL(10,1) NOT NULL");         // класс бетона из текста в дробное число
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Дата` `Дата` DATE NOT NULL"); //преобразуем текст в дату  ?>       

<?php
$connection->query( "CREATE TABLE excel2mysql0_k2 LIKE excel2mysql0_k");
$connection->query("insert into `excel2mysql0_k2` (`Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF`) SELECT `Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF` FROM `excel2mysql0_k`
LEFT JOIN `excel2mysql0_k2`
using(`Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF`)
WHERE `excel2mysql0_k2`.`ID_TAB` IS NULL"); 
} else {  echo "Файл не загружен.\n</br>"; }
?>
<form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="3"/>
<input type="submit">
</form>

<table align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px">
<tbody>
<tr><td>
<table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px"  >
  <tr>
   <td align="center" style="width:104px; height:20px;">№</td>	
   <td align="center" style="width:104px; height:20px;">Дата <br/>изготовления</td>					
   <td align="center" style="width:139px; height:20px;">Наименование <br/>изделия</td>				
   <td align="center" style="width:104px; height:20px;">Класс <br/>бетона</td>						
   <td align="center" style="width:104px; height:20px;">Прочность, МПа</td>							
   <td align="center" style="width:104px; height:20px;">Требуемая <br/>прочность, МПа</td>			
   <td align="center" style="width:104px; height:20px;">Прочность, %</td>   							
   <td align="center" style="width:104px; height:20px;">Добавка</td>  								
  </tr>
  </table>
  </td></tr>
  <tr><td>
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px"  class="table_XLS">
<?php  $nomer_str=0;
$result = $connection->query("SELECT * FROM excel2mysql0_k2 where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");// Запрос исходной таблицы с данными
while($row = $result->fetch_array()){
 extract ($row);?>
<tr >
<td align="center" style="width:40px; height:20px;"><?=++$nomer_str; ?></td>
<td align="center" style="width:40px; height:20px;"><?=$Дата?></td>
<td  style="width:139px; height:20px;"><?=$row['Наименование_изделия']?></td>
<td align="center" style="width:40px; height:20px;"><?=$row['Класс_бетона']?></td>
<td align="center" onblur="$('#proch').bind('blur', function(evt) {
    $.post('/some/url/to/post/to', { 
            $('#proch').attr('name') : $('#proch').val(), 
           
        }, 
        function(data) {
            alert('Done');
            alert(data);
        }
    });
});" style="width:40px; height:20px;" contenteditable="true" id="proch"><?=$row['Прочность_МПа']?></td>
<td align="center" style="width:40px; height:20px;"><?=$row['Требуемая_прочность_МПа']?></td>
<td align="center" style="width:40px; height:20px;"><?=$row['Прочность_проценты']?></td>
<td align="center" style="width:40px; height:20px;"><?=$row['Добавка']?></td>
</tr>
  <?php } ?>
  </table>
</td></tr>
</tbody>
</table>


</div>