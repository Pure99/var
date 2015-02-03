<html>
    <head>
        <title>Коэффициент вариации</title>
        <meta charset="utf-8" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body>
<p  align=center>  <a  href="/../var/">  <font  size="20" color="red" face="Arial">  ;-) </font>  </a>    </p>
<h3>Товарный бетон "Тека"</h3>
<p><a href="fact_TT.php">Открыть таблицу</a></p>
<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "file_tt.xlsx")) { // загружаемый файл всегда будет сохраняться под одним именем
    echo "Файл корректен и был успешно загружен.\n";
	echo "<h3>Информация о загруженном на сервер файле: </h3>";
	echo "<p><b>Оригинальное имя загруженного файла: ".@$_FILES['userfile']['name']."</b></p>";
	echo "<p><b>Mime-тип загруженного файла: ".@$_FILES['userfile']['type']."</b></p>";
	echo "<p><b>Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</b></p>";
	echo "<p><b>Временное имя файла: ".@$_FILES['userfile']['tmp_name']."</b></p>";
} else {
    echo "Файл не загружен.\n</br>";
}
// Подключаем библиотеку
require_once "PHPExcel.php";
include ('../config.php');
// Загружаем файл Excel
$PHPExcel_file = PHPExcel_IOFactory::load("./file_tt.xlsx");
// Преобразуем первый лист Excel в таблицу MySQL
$PHPExcel_file->setActiveSheetIndex(0);
echo excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_tt", 2) ? "Таблица EXCEL успешно преобразована в базу данных.\n" : "Таблица в файле не соответствует требуемому формату\n";
// Перебираем все листы Excel и преобразуем в таблицу MySQL
//foreach ($PHPExcel_file->getWorksheetIterator() as $index => $worksheet) {
  //echo excel2mysql($worksheet, $connection, "excel2mysql" . ($index != 0 ? $index : ""), 1) ? "Таблица EXCEL успешно преобразована в базу данных\n" : "FAIL\n";
//}

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
?>
  </body>
</html>