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
function interpol($x) {    // функция интерполяции коэффициента вариации пригодится ниже
    if (0 < $x & $x < 6): $z = 1.07;
    elseif (6 <= $x & $x < 8): $z = 0.01 * $x + 1.01;
    elseif (8 <= $x & $x < 9): $z = 0.02 * $x + 0.93;
    elseif (9 <= $x & $x < 10): $z = 0.03 * $x + 0.84;
    elseif (10 <= $x & $x < 11): $z = 0.04 * $x + 0.74;
    elseif (11 <= $x & $x <= 16): $z = 0.05 * $x + 0.63;
    else: $z = "недопустимо";
    endif;
    return $z;
}
function alfa($a) {
    if ($a == 2):       $k = 1.13;    // определение коэффициента альфа
    elseif ($a == 3):   $k = 1.69;
    elseif ($a == 4):   $k = 2.06;
    elseif ($a == 5):   $k = 2.33;
    elseif ($a == 6):   $k = 2.5;
    else:               $k = 1;
    endif;
    return $k;
}

// Функция преобразования листа Excel в таблицу MySQL, с учетом объединенных строк и столбцов.
// Значения берутся уже вычисленными. Параметры:
//     $worksheet - лист Excel
//     $connection - соединение с MySQL (mysqli)
//     $table_name - имя таблицы MySQL
//     $columns_name_line - строка с именами столбцов таблицы MySQL (0 - имена типа column + n)
function excel2mysql($worksheet, $connection, $table_name, $columns_name_line = 0) {
    if (!$connection->connect_error) {  // Проверяем соединение с MySQL
        $columns_str = "";  // Строка для названий столбцов таблицы MySQL
        $columns_count = PHPExcel_Cell::columnIndexFromString($worksheet->getHighestColumn()); // Количество столбцов на листе Excel
        for ($column = 0; $column < $columns_count; $column++) { // Перебираем столбцы листа Excel и генерируем строку с именами через запятую
            $columns_str .= ($columns_name_line == 0 ? "column" . $column : $worksheet->getCellByColumnAndRow($column, $columns_name_line)->getCalculatedValue()) . ",";
        }
        $columns_str = substr($columns_str, 0, -1); // Обрезаем строку, убирая запятую в конце
        if ($connection->query("DROP TABLE IF EXISTS " . $table_name)) { // Удаляем таблицу MySQL, если она существовала
            // Создаем таблицу MySQL
            if ($connection->query("CREATE TABLE " . $table_name . " (" . str_replace(",", " TEXT NOT NULL,", $columns_str) . " TEXT NOT NULL)")) {
                $rows_count = $worksheet->getHighestRow(); // Количество строк на листе Excel
                for ($row = $columns_name_line + 1; $row <= $rows_count; $row++) {// Перебираем строки листа Excel    
                    $value_str = ""; // Строка со значениями всех столбцов в строке листа Excel
                    for ($column = 0; $column < $columns_count; $column++) {// Перебираем столбцы листа Excel   
                        $merged_value = ""; // Строка со значением объединенных ячеек листа Excel
                        $cell = $worksheet->getCellByColumnAndRow($column, $row); // Ячейка листа Excel
                        foreach ($worksheet->getMergeCells() as $mergedCells) {// Перебираем массив объединенных ячеек листа Excel
                            if ($cell->isInRange($mergedCells)) {// Если текущая ячейка - объединенная,
                                // то вычисляем значение первой объединенной ячейки, и используем её в качестве значения текущей ячейки
                                $merged_value = $worksheet->getCell(explode(":", $mergedCells)[0])->getCalculatedValue();
                                break;
                            }
                        }
                        // Проверяем, что ячейка не объединенная: если нет, то берем ее значение, иначе значение первой объединенной ячейки
                        $value_str .= "'" . (strlen($merged_value) == 0 ? $cell->getCalculatedValue() : $merged_value) . "',";
                    }
                    $value_str = substr($value_str, 0, -1); // Обрезаем строку, убирая запятую в конце
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
