<?php
$db_host = 'localhost'; //имя MySQL-сервера
$db_user = 'root'; // имя пользователя
$db_pass = 'aaaassss'; // пароль
$db_name = 'base'; // имя БАЗЫ
// устанавливаем соединение с БД

$connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
$connection->set_charset("utf8");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
function interpol ($x){				// функция интерполяции коэффициента вариации пригодится ниже

    if ($x < 6) {
        $z = 1.07;
    }
    if (6 <= $x) {
        $z = 0.01 * $x + 1.01;
    }
    if (8 <= $x) {
        $z = 0.02 * $x + 0.93;
    }
    if (9 <= $x) {
        $z = 0.03 * $x + 0.84;
    }     							
    if (10 <= $x) {
        $z = 0.04 * $x + 0.74;
    }
    if (11 <= $x) {
        $z = 0.05 * $x + 0.63;
    }
    if (16 < $x) {
        $z = 'недопустимо';
    }
	if (0==$x){ $z='недопустимо';}
    return $z;}
function alfa ($a){$k=1;
    if ($a==2)  $k=1.13;    // определение коэффициента альфа
	if ($a==3)  $k=1.69;
    if ($a==4)  $k=2.06;
    if ($a==5)  $k=2.33;
	if ($a==6)  $k=2.5;
	return $k;}
	
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
?>
