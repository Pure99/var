<script type="text/javascript">
function replace() {
x = Math.round(Math.random()*500);
y = Math.round(Math.random()*500);
h = Math.round(Math.random()*50)+100;
w = Math.round(Math.random()*50)+100;
bg = Math.round(Math.random()*1000000);
r = Math.round(Math.random()*100);
document.getElementById('mb').style="position:absolute;\n"+"left:"+x+";\n"+"top:"+y+";\n"+"width:"+w+";\n"+"height:"+h+";\n"+"background-color:"+bg+";\n"+"border-radius:"+r+"px;\n";
}
</script>  
<script type="text/javascript">
function mouseCoords(e) {
 // создание виртуального образа кнопки
var btn = document.createElement (('v' == '\v') ? '<input name="myName">' : 'input'); btn.name = 'myName';
btn.type = 'button'; // или 'submit', или 'reset';
btn.id = 'myUniqueID';
btn.value = 'Дави!'; // или 'Отправить', или 'Очистить'...
btn.style.cssText = 'color: red; margin-left: 100px; position:absolute; left:1000px; top:500px;...';
 
// "приживление" кнопки
// вариант первый: перед закрывающим тегом какого-нить тега-контейнера с id="myPlace"
//document.getElementById ('myPlace').appendChild (btn);
 
// вариант второй: перед открывающим тегом какого-нить тега c id="myElem"
document.getElementById ('myElem').parentNode.insertBefore (btn, document.getElementById ('myElem'));
this.style.bgColor='#CCCCCC';
var btnn=document.getElementById ('this.id');
btnn.style.cssText = '';


}
function mouseout(e) {
var el = document.getElementById('myUniqueID');
el.parentNode.removeChild(el);
var btnn=document.getElementById ('b496');
btnn.style.cssText = 'display:none';

}
</script>  
<h3>Конструкционный бетон</h3>
	<!-- Тип кодирования данных, enctype, ДОЛЖЕН БЫТЬ указан ИМЕННО так -->
<div align="left" class="pole jumbotron" style="position:relative"><form enctype="multipart/form-data" action="index.php?viewInfo=3" method="POST">
    <input type="hidden" name="MAX_FILE_SIZE" value="300000" /> <!-- Поле MAX_FILE_SIZE должно быть указано до поля загрузки файла -->
    <!-- Название элемента input определяет имя в массиве $_FILES -->
Отправить этот файл:<input name="userfile" type="file" style="display:inline"/><input type="submit" class="btn btn-primary" value="Отправить файл"/></form></div>
<?php
if (@copy($_FILES['userfile']['tmp_name'], "var/Konst/file.xlsx")) {   // загружаемый файл всегда будет сохраняться под одним именем
echo "<div class='alert alert-success' role='alert'>Файл \"" .@$_FILES['userfile']['name']. "\" был успешно загружен. Размер загруженного файла в байтах: ".@$_FILES['userfile']['size']."</div>";
require_once "PHPExcel.php";// Подключаем библиотеку
@$PHPExcel_file = PHPExcel_IOFactory::load("var/Konst/file.xlsx"); // Загружаем файл Excel
$PHPExcel_file->setActiveSheetIndex(0);// Преобразуем первый лист Excel в таблицу MySQL
if ( excel2mysql($PHPExcel_file->getActiveSheet(), $connection, "excel2mysql0_k", 2)) {
  echo "<div class='alert alert-success' role='alert'>Таблица EXCEL успешно преобразована в базу данных.</div>" ; 
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
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Прочность_проценты` `Прочность_проценты` DECIMAL(10,0) NOT NULL");
$connection->query("ALTER TABLE `excel2mysql0_k` ADD `KOEF` INT(2) NOT NULL ");  //добавить столбец KOEF 
$connection->query("UPDATE `excel2mysql0_k` SET `KOEF`=1");                      // записать единицу в KOEF   
$connection->query("DELETE FROM `base`.`excel2mysql0_k` WHERE `excel2mysql0_k`.`Наименование_изделия` = ''");   //удалить строки с пустыми полями
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Класс_бетона` `Класс_бетона` DECIMAL(10,1) NOT NULL");         // класс бетона из текста в дробное число
$connection->query("ALTER TABLE `excel2mysql0_k` CHANGE `Дата` `Дата` DATE NOT NULL"); //преобразуем текст в дату       
$connection->query( "CREATE TABLE excel2mysql0_k2 LIKE excel2mysql0_k"); //создать вторую таблицу
$connection->query("insert into `excel2mysql0_k2` (`Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF`) SELECT `Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF` FROM `excel2mysql0_k`
LEFT JOIN `excel2mysql0_k2`
using(`Дата`, `Наименование_изделия`, `Класс_бетона`, `Прочность_МПа`, `Требуемая_прочность_МПа`, `Прочность_проценты`, `Добавка`, `KOEF`)
WHERE `excel2mysql0_k2`.`ID_TAB` IS NULL"); // синхронизировать таблицы
} else { echo "<div class='alert alert-danger' role='alert'>Таблица в файле не соответствует требуемому формату.</div>";}
}  ?>
<div class="pole jumbotron" style="position:fixed"><form  name="Form" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="3"/>
<br><input type="submit" class="btn btn-primary">
</form></div>
<div align="center"><table class="table-autostripe table-rowshade-alternate table-autosort table-autofilter table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount" align="center" bordercolor="black" border="1px" bgcolor="#eaeae" cellpadding="0px" cellspacing="0px" id="table1" style="border-right-width:0px"      >
<thead>
 
   <td class="table-filterable table-sortable:numeric" align="center" style="width:50px; height:20px;">№<br></td>	
   <td class="table-filterable table-sortable:default" align="center" style="width:104px; height:20px;">Дата <br>изготовления</td>					
   <td class="table-filterable table-sortable:default" align="center" style="width:122px; height:20px;">Наименование <br>изделия</td>				
   <td class="table-filterable table-sortable:numeric" align="center" style="width:104px; height:20px;">Класс <br>бетона</td>						
   <td class="table-filterable table-sortable:numeric" align="center" style="width:104px; height:20px;">Прочность, МПа</td>							
   <td class="table-filterable table-sortable:numeric" align="center" style="width:114px; height:20px;">Требуемая прочность, МПа</td>			
   <td class="table-filterable table-sortable:numeric" align="center" style="width:104px; height:20px;">Прочность, %<br></td>   							
   <td class="table-filterable table-sortable:default" align="center" style="width:104px; height:20px;">Добавка<br></td>  								
  
  </thead>
<?php  $nomer_str=0;
$result = $connection->query("SELECT * FROM excel2mysql0_k2 where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");// Запрос исходной таблицы с данными
while($row = $result->fetch_array()){
 extract ($row);?>
<tr  onmouseover="mouseCoords(event)" onmouseout="mouseout(event)"> 
<td align="center" ><?=++$nomer_str; ?></td>
<td align="center" ><?=$Дата?></td>
<td align="left"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_k2 izdelie" data-type="text" data-placement="right" data-title="Наименование изделия"><?=$row['Наименование_изделия']?></span></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_k2 klass_betona" data-type="text" data-placement="right" data-title="Класс бетона"><?=$row['Класс_бетона']?></span></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_k2 prochnost" data-type="text" data-placement="right" data-title="Прочность, МПа"><?=$row['Прочность_МПа']?></span></td>
<td align="center"><span id="<?=$row['ID_TAB']?>" class="excel2mysql0_k2 tr_prochnost" data-type="text" data-placement="right" data-title="Требуемая прочность"><?=$row['Требуемая_прочность_МПа']?></span></td>
<td align="center" <?if ($row['Прочность_проценты']<100) echo "style='color:red'";?>><?=$row['Прочность_проценты']?></td>
<td align="center"><?=$row['Добавка']?></td>
<td style="border:0px; border-right-width:0px !important;"> <input id="b<?=$row['ID_TAB']?>" type=button class="btn btn-primary" style="display:none"> </td>
</tr>
  <?php } ?>
 </table></div><div id="myElem"></div>

     


