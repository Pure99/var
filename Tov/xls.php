<html>
    <head>
        <title>Официальный Коэффициент вариации</title>
        <meta charset="utf-8" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet" media="all">
        <script src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <style>
        </style>
    </head>
    <body>
<p  align=center>  <a  href="http://192.168.100.140/var/">  <font  size="20" color="red" face="Arial">  ;-) </font>  </a>    </p>
<h3>Товарный бетон</h3>
<p><a href="fact_T.php">Открыть таблицу</a></p>
<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<form enctype="multipart/form-data" action="<?=$_SERVER['PHP_SELF']?>" method="POST">
    <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" />
    <!-- Название элемента input определяет имя в массиве $_FILES -->
    Отправить этот файл: <input name="userfile" type="file" />
    <input type="submit" value="Send File" />
</form>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "file.xlsx")) { // загружаемый файл всегда будет сохраняться под одним именем
    echo "Файл корректен и был успешно загружен.\n";
} else {
    echo "Файл не загружен\n";
}
echo "<h3>Информация о загруженном на сервер файле: </h3>";
echo "<p><b>Оригинальное имя загруженного файла: ".@$_FILES['userfile']['name']."</b></p>";
echo "<p><b>Mime-тип загруженного файла: ".@$_FILES['userfile']['type']."</b></p>";
echo "<p><b>Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</b></p>";
echo "<p><b>Временное имя файла: ".@$_FILES['userfile']['tmp_name']."</b></p>";
// Подключаем библиотеку
require_once "PHPExcel.php";
include ('config.php');
// Функция преобразования листа Excel в таблицу MySQL, с учетом объединенных строк и столбцов.
// Значения берутся уже вычисленными. Параметры:
//     $worksheet - лист Excel
//     $connection - соединение с MySQL (mysqli)
//     $table_name - имя таблицы MySQL
//     $columns_name_line - строка с именами столбцов таблицы MySQL (0 - имена типа column + n)
function excel2mysql($worksheet, $connection, $table_name, $columns_name_line = 0) {
  // Проверяем соединение с MySQL
  if (!$connection->connect_error) {
    // Строка для названий столбцов таблицы MySQL
    $columns_str = "";
    // Количество столбцов на листе Excel
    $columns_count = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn());

    // Перебираем столбцы листа Excel и генерируем строку с именами через запятую
    for ($column = 0; $column < $columns_count; $column++) {
      $columns_str .= ($columns_name_line == 0 ? "column" . $column : $worksheet->getCellByColumnAndRow($column, $columns_name_line)->getCalculatedValue()) . ",";
    }

    // Обрезаем строку, убирая запятую в конце
    $columns_str = substr($columns_str, 0, -1);

    // Удаляем таблицу MySQL, если она существовала
    if ($connection->query("DROP TABLE IF EXISTS " . $table_name)) {
      // Создаем таблицу MySQL
      if ($connection->query("CREATE TABLE " . $table_name . " (" . str_replace(",", " TEXT NOT NULL,", $columns_str) . " TEXT NOT NULL)")) {
        // Количество строк на листе Excel
        $rows_count = $worksheet->getHighestRow();

        // Перебираем строки листа Excel
        for ($row = $columns_name_line + 1; $row <= $rows_count; $row++) {
          // Строка со значениями всех столбцов в строке листа Excel
          $value_str = "";

          // Перебираем столбцы листа Excel
          for ($column = 0; $column < $columns_count; $column++) {
            // Строка со значением объединенных ячеек листа Excel
            $merged_value = "";
            // Ячейка листа Excel
            $cell = $worksheet->getCellByColumnAndRow($column, $row);

            // Перебираем массив объединенных ячеек листа Excel
            foreach ($worksheet->getMergeCells() as $mergedCells) {
              // Если текущая ячейка - объединенная,
              if ($cell->isInRange($mergedCells)) {
                // то вычисляем значение первой объединенной ячейки, и используем её в качестве значения
                // текущей ячейки
                $merged_value = $worksheet->getCell(explode(":", $mergedCells)[0])->getCalculatedValue();
                break;
              }
            }

            // Проверяем, что ячейка не объединенная: если нет, то берем ее значение, иначе значение первой
            // объединенной ячейки
            $value_str .= "'" . (strlen($merged_value) == 0 ? $cell->getCalculatedValue() : $merged_value) . "',";
          }

          // Обрезаем строку, убирая запятую в конце
          $value_str = substr($value_str, 0, -1);

          // Добавляем строку в таблицу MySQL
          $connection->query("INSERT INTO " . $table_name . " (" . $columns_str . ") VALUES (" . $value_str . ")");
        }
      } else {
        return false;
      }
    } else {
      return false;
    }
  } else {
    return false;
  }

  return true;
  
}

// Соединение с базой MySQL
$connection = new mysqli("localhost", "root", "", "base");
// Выбираем кодировку UTF-8
$connection->set_charset("utf8");

// Загружаем файл Excel
$PHPExcel_file = PHPExcel_IOFactory::load("./file.xlsx"); 

// Преобразуем первый лист Excel в таблицу MySQL
$PHPExcel_file->setActiveSheetIndex(0);
echo excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_t", 2) ? "Таблица EXCEL успешно преобразована в базу данных\n" : "Таблица в файле не соответствует требуемому формату\n";

// Перебираем все листы Excel и преобразуем в таблицу MySQL
//foreach ($PHPExcel_file->getWorksheetIterator() as $index => $worksheet) {
  //echo excel2mysql($worksheet, $connection, "excel2mysql" . ($index != 0 ? $index : ""), 1) ? "Таблица EXCEL успешно преобразована в базу данных\n" : "FAIL\n";
//}

mysql_query("ALTER TABLE excel2mysql0_t ADD ID_TAB INT(10) NOT NULL AUTO_INCREMENT FIRST, ADD PRIMARY KEY (`ID_TAB`)");   // Добавляем в таблицу столбец ID_TAB с автоинкрементом
$result = mysql_query("SELECT `Дата`,`ID_TAB` FROM excel2mysql0_t");
while($row = mysql_fetch_array($result)){       //Преобразуем формат даты 
extract ($row);
$excel_timestamp = $row['Дата']-25568; // 1970-01-01 is day 25567. 
$php_timestamp = mktime(0,0,0,1,$excel_timestamp,1970); // No, really - this works! 
$mysql_timestamp = date('Y-m-d', $php_timestamp); // Or whatever the format is. 
mysql_query("UPDATE base.excel2mysql0_t SET Дата = '$mysql_timestamp' WHERE excel2mysql0_t.ID_TAB = '$ID_TAB'");//запись даты в базу
 }
mysql_query("ALTER TABLE `excel2mysql0_t` CHANGE `Прочность28` `Прочность28` DECIMAL(10,1) NOT NULL"); // Добовить ноль росле запятой 
mysql_query("ALTER TABLE `excel2mysql0_t` CHANGE `Требуемая_прочность_МПа` `Требуемая_прочность_МПа` DECIMAL(10,1) NOT NULL");
mysql_query("ALTER TABLE `excel2mysql0_t` ADD `KOEF` INT(2) NOT NULL ");  //добавить столбец KOEF 
mysql_query("UPDATE `excel2mysql0_t` SET `KOEF`=1");                      // записать единицу в KOEF   
mysql_query("DELETE FROM `base`.`excel2mysql0_t` WHERE `excel2mysql0_t`.`Класс` = ''");   //удалить строки с пустыми полями
mysql_query("ALTER TABLE `excel2mysql0_t` CHANGE `Дата` `Дата` DATE NOT NULL");  //преобразуем текст в дату

?>

    </body>
</html>