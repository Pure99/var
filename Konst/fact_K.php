<html>
    <head>
        <title>Коэффициент вариации</title>
        <meta charset="utf-8" />
        <link href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="style.css" rel="stylesheet" media="all">
		<link media="print, handheld" rel="stylesheet" href="print.css">
        <script src="http://yandex.st/jquery/2.0.3/jquery.min.js"></script>
        <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
        <style>
        </style>
    </head>
    <body>
<p align=center>  <a  href="/../var/">  <font  size="20" color="red" face="Arial">  ;-) </font>  </a>    </p>
<p><a href="xls.php">Преобразовать таблицу exel в базу данных</a></p>
<p><a href="ofsl_K.php">Посчитать официальный коэффициент вариации</a></p>
<p>
﻿<?php
include ('config.php');
	$data1=isset($_GET['data1']) ? $_GET['data1'] : date("Y-m-d",strtotime("first day of -2 month"));
	$data2=isset($_GET['data2']) ? $_GET['data2'] : date("Y-m-d",strtotime("last day of -2 month"));
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
?>
<form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" value="<?=$data2?>">
<input type="submit">
</form>
<table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table1">
    <tr>
   <td align="center">Дата <br/>изготовления</td>					
   <td align="center">Наименование <br/>изделия</td>				
   <td align="center">Класс <br/>бетона</td>						
   <td align="center">Прочность, МПа</td>							
   <td align="center">Требуемая <br/>прочность, МПа</td>			
   <td align="center">Прочность, %</td>   							
   <td align="center">Добавка</td>  								
  </tr>
<?php
 $result = mysql_query("SELECT * FROM excel2mysql0_k where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");				// Запрос основной таблицы
while($row = mysql_fetch_array($result)){
 extract ($row);?>
  <tr >
<td ><input type="date" name="Date" onchange="alert (this.value);" value="<?php echo $row['Дата']?>" style="width:140px; height:20px; border:2px;" /></td>
<td><input type="text" name="Name" value="<?=$row['Наименование_изделия']?>" style="width:120px; height:20px; border:2px"  /></td>
<td><input type="text" name="Class" value="<?=$row['Класс_бетона']?>" style="width:50px; height:20px; border:2px; text-align:center;" /></td>
<td><input type="text" name="Strong_MPa" value="<?=$row['Прочность_МПа']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Strong_MPa_Tr" value="<?=$row['Требуемая_прочность_МПа']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Strong_MPa_P" value="<?=$row['Прочность_проценты']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Добавка']?>" style="width:120px; height:20px; border:2px"   /></td>
</tr>
  <?php }?>
  </table>
  <br/>
 
</p>

<p>Фактический коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = mysql_query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k`.`Класс_бетона` ASC");
  while($row = mysql_fetch_array($result)){           // Список всех наименований изделий
   extract ($row);
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = mysql_query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия'");  
  while($row_1 = mysql_fetch_array($result_1)){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность_МПа;
  if   ($Прочность_МПа > $P_max) $P_max=$Прочность_МПа;        // определение максимального значения
  if   ($Прочность_МПа < $P_min) $P_min=$Прочность_МПа;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0;
   
   $result_2 = mysql_query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and  `Наименование_изделия` like '$Наименование_изделия'"); 
  while($row_2 = mysql_fetch_array($result_2)){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s);
  
   } ?>
 
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
	<caption><?php echo $row['Наименование_изделия'] ; echo ' Класс B'; echo rtrim(rtrim($Класс_бетона,'0'), '.')	;?></caption>
  <tr>	
   <td align="center">№п/п</td>
   <td align="center">Дата <br/>изготовления</td>
   <td align="center">Прочность <br/>серии <br/> образцов <br/>Ri, Мпа</td>
   <td align="center">Фактическая<br/> прочность<br/>Rm, Мпа</td>
   <td align="center">Ri-Rm</td>
   <td align="center">(Ri-Rm)2</td>
   <td align="center">Размах <br/> единичных <br/> значений <br/>Wm, Мпа</td>   
   <td align="center">Среднее <br/>квадротическое <br/>отклонение<br/> Sm, МПа</td>  
   <td align="center">Коэффициент <br/>вариации<br/> Vm, %</td>  
   <td align="center">Коэффициент <br/>требуемой <br/>прочности Кт</td>  
   <td align="center">Требуемая <br/>прочность<br/> Rt, МПа</td>  
  </tr>
  
  <?php 	
  $n=0 ; //Начало вложенного цикла  
  $result_3 = mysql_query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and  `Наименование_изделия` like '$Наименование_изделия'"); 
  while($row_3 = mysql_fetch_array($result_3)){ 
  extract ($row_3);
  $n=$n+1;
  ?>
  <tr>	
   <td align="center"><?php echo $n?></td>
   <td align="center"><?php echo $Дата?></td>
   <td align="center"><?php echo $Прочность_МПа?></td>
   <td align="center"></td>
   <td align="center"><?php  echo round($Прочность_МПа-$mid_s,1) ?></td>
   <td align="center"><?php  echo round(($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s),1)?> </td>   
   <td align="center"></td>  
   <td align="center"></td>  
   <td align="center"></td>  
   <td align="center"></td>
   <td align="center"></td>
  </tr>
  <?php        // Ниже последняя строчка таблиц с итогами
  }     ?>
  
  <tr>
   <td align="center">Итоги</td>
   <td align="center"></td>
   <td align="center"><?php echo $sum ?></td>
   <td align="center"><?php echo round($mid_s,1); $Mas_Mid[]=$mid_s; ?></td>
   <td align="center"></td>
   <td align="center"><?php echo round($sumR,1)  ?> </td>
   <td align="center"><?php echo $P_max-$P_min     ?></td>   
   <td align="center"><?php if ($b>6) {echo number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '') ;} else {echo number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', '');}?></td>  
   <td align="center"><?php  echo  number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', '') ; $Mas_Var[]=$Vm;?></td>  
   <td align="center"><?php echo $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ?></td>  
   <td align="center"><?php echo  number_format(round($Rt=$row['Класс_бетона'] *  $Kt,1), 1, '.', '');  ?> </td>  
  </tr>
  </table>
 <br/>
 <p>
 Rm ≥ Rт				<br/>		
Rmin = 		<?echo $Rt-4?>			<br/>
В˂Rmini≥Rmin						<br/>
					<br/>
</p>
<p align=center >------------------------------------------------------------------------------------------------------------------------</p>
   <?php } ?>
   
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption contenteditable="true">Результаты статистического метода контроля прочности конструкционного бетона на 28 суток по ГОСТ 18105-2010 фактический	</caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Наименование изделия</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Средняя прочность Rm, МПа</td>
   <td align="center">Прочность по ГОСТ, МПа</td>
 </tr>			
 <? $l=0;
 $result = mysql_query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k`.`Класс_бетона` ASC");				// Запрос основной таблицы
while($row = mysql_fetch_array($result)){
 extract ($row);

 ?>
 		<tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?=$Наименование_изделия?></td>
   <td align="center"><?echo str_replace('.',',',(rtrim(rtrim($Класс_бетона,'0'), '.')))?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Mid[$l],1), 1, '.', '')))?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Класс_бетона*1.31,1), 1, '.', '')))?></td>
 </tr>			
			
<?$l=$l+1;	
					}?>
</table>	
  
  

<!--
<table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
  <tr>
   <td align="center">Дата изготовления</td>
   <td align="center">Наименование изделия</td>
   <td align="center">Класс бетона</td>
   <td align="center">Прочность, МПа</td>
   <td align="center">Требуемая прочность, МПа</td>
   <td align="center">Прочность, %</td>
   <td align="center">Добавка, %</td>
  </tr>
  
  
  
  
<?php






  $res=mysql_query("SELECT * FROM excel2mysql0_k WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' ");
  while($q=mysql_fetch_array($res)){
  extract ($q);?>
 <tr id="pol<?=$ID_TAB?>">
   <td contenteditable="true" onblur="save('Дата',$(this).text(),'<?=$Дата?>');"><?=$Дата?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Наименование_изделия?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Класс_бетона?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Прочность_МПа?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Требуемая_прочность_МПа?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Прочность_проценты?></td>
   <td contenteditable="true" onblur="save('DOP_GR',$(this).text(),'<?=$ID_GR?>');"><?=$Добавка?></td>
 </tr>
  <?php }?>
 </table>



-->




    </body>
</html>




