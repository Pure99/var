<html>
    <head>
        <title>Официальный Коэффициент вариации</title>
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
<p  align=center>  <a  href="/../var/">  <font  size="20" color="red" face="Arial">  ;-) </font>  </a>    </p>
﻿<p><a href="xls.php">Преобразовать таблицу exel в базу данных</a></p>
<?php
include ('config.php');
	$data1=isset($_GET['data1']) ? $_GET['data1'] : date("Y-m-d",strtotime("first day of -2 month"));
	$data2=isset($_GET['data2']) ? $_GET['data2'] : date("Y-m-d",strtotime("last day of -2 month"));
	$koef_var=isset($_GET['koef_var']) ? $_GET['koef_var'] : 8.7;
function interpol ($x){

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
    }     							// функция интерполяции коэффициента вариации пригодится ниже
    if (10 <= $x) {
        $z = 0.04 * $x + 0.74;
    }
    if (11 <= $x) {
        $z = 0.05 * $x + 0.63;
    }
    if (16 <= $x) {
        $z = 1.43;
    }
	if (0==$x) {
	$z='недопустимо';
	}
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
Коэффициент вариации:<input type="text" name="koef_var" value="<?=$koef_var?>">
<input type="submit">
</form>
<p>
<table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table1">
    <tr>
   <td align="center">Дата <br/>изготовления</td>		
   <td align="center">Класс <br/>бетона</td>	
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

mysql_query("update `base`.`excel2mysql0_tt` set `KOEF` = 0 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_tt`.`Прочность_28_проценты` < 100"); 

 $result = mysql_query("SELECT * FROM excel2mysql0_tt WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_tt`.`KOEF` = 1");				// Запрос основной таблицы
while($row = mysql_fetch_array($result)){
 extract ($row);?>
  <tr >
<td ><input type="date" name="Date" onchange="alert (this.value);" value="<?php echo $row['Дата']?>" style="width:140px; height:20px; border:2px;" /></td>
<td><input type="text" name="Name" value="<?=$row['Класс']?>" style="width:130px; height:20px; border:2px"  /></td>
<td><input type="text" name="Strong_MPa" value="<?=$row['Прочность7']?>" style="width:120px; height:20px; border:2px;text-align:center"/></td>
<td><input type="text" name="Strong_MPa_Tr" value="<?=$row['Прочность28']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Strong_MPa_P" value="<?=$row['Требуемая_прочность_МПа']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_7_проценты']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_28_проценты']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прирост']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Место_отгрузки_БС']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Добавка']?>" style="width:110px; height:20px; border:2px"   /></td>

</tr>
  <?php }?>
  </table>
  <br/>
 
</p>

<p>Официальный коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = mysql_query("SELECT  `Класс`,`Дата` FROM `excel2mysql0_tt` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ASC");
  while($row = mysql_fetch_array($result)){           // Список всех наименований изделий
   extract ($row);
   do {
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = mysql_query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'");  
  
  while($row_1 = mysql_fetch_array($result_1)){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность28;
  if   ($Прочность28 > $P_max) $P_max=$Прочность28;        // определение максимального значения
  if   ($Прочность28 < $P_min) $P_min=$Прочность28;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0; 
   $DFR=0;       // Максимальная разность квадратов
   $DFP=0;
   $result_2 = mysql_query("SELECT `Дата`,  `Прочность28`,`Прочность_28_проценты` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'"); 
   
 while($row_2 = mysql_fetch_array($result_2)){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность28-$mid_s)*($Прочность28-$mid_s);
   if   (($Прочность28-$mid_s)*($Прочность28-$mid_s) > $DFR) {$DFR=($Прочность28-$mid_s)*($Прочность28-$mid_s); $DFP=$Прочность28; }
   } 
  // echo $DFR.' -Максимальная разность квадратов';               
  // echo '<br/>';
  // echo $DFP.' -Прочность, которую нужно отсеять, если коэффициент вариации больше 9';                     // Прочность, которую нужно отсеять
  // echo '<br/>';
   if ($b>6) {$Sm=sqrt($sumR/($b-1)) ;} else {$Sm=($P_max-$P_min)/alfa($b);}
  // echo $Sm.' -Среднее квадротическое отклонение';
  // echo '<br/>';
   $Vm=$Sm*100/$mid_s;
   //echo $Vm.' -Коэффициент вариации';
   if ($Vm > $koef_var) {
  mysql_query ("update `base`.`excel2mysql0_tt` set `KOEF` = 0  WHERE `excel2mysql0_tt`.`Прочность28` = $DFP and `Класс` like '$Класс' ");
  }
  } while ($Vm > $koef_var);?>
   
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
	<caption><?php echo ' Класс ';  echo$row['Класс'] ;  ?></caption>
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
  $result_3 = mysql_query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'"); 
  while($row_3 = mysql_fetch_array($result_3)){ 
  extract ($row_3);
  $n=$n+1;
  ?>
  <tr>	
   <td align="center"><?php echo$n?></td>
   <td align="center"><?php echo$Дата?></td>
   <td align="center"><?php echo$Прочность28?></td>
   <td align="center"></td>
   <td align="center"><?php  echo round($Прочность28-$mid_s,1) ?></td>
   <td align="center"><?php  echo round(($Прочность28-$mid_s)*($Прочность28-$mid_s),1)?> </td>   
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
   <td align="center"><?php echo round($mid_s,1)?></td>
   <td align="center"></td>
   <td align="center"><?php echo round($sumR,1)  ?> </td>
   <td align="center"><?php echo $P_max-$P_min     ?></td>   
   <td align="center"><?php if ($b>6) {echo number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '') ;} else {echo number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', '');}?></td>  
   <td align="center"><?php  echo  number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', '') ;$Mas_Var[]=$Vm ?> </td>  
   <td align="center"><?php echo $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ?></td>  
   <td align="center"><?php  preg_match("/В(.*?)(П|С|\s)/", str_replace(',','.',$Класс), $matches);  echo $Rt=$matches[1]*$Kt; $Mas_Rt[]=$Rt ?> </td>  
  </tr>
  </table>
  <br/>
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
 <?php $result_4 = mysql_query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '0'"); 
  while($row_4 = mysql_fetch_array($result_4)){ 
  extract ($row_4);?>
 <tr>
   <td align="center"><?echo$Дата?></td>
   <td align="center"><?echo$Прочность28?></td>
  </tr>
   <?php } ?>
  </table>
 <br/>
 <p>
 Rm ≥ Rт		<?=$mid_s;?> МПа > <?=$Rt;?><br/>		
Rmin = <?=$Rt-4?> МПа<br/>
В˂Rmini≥Rmin			<?=$matches[1]?>˂<?=$P_min?>˃<?=$Rt-4?><br/>
Заключение: Партия бетона подлежит приемке в соответствии с требованиями<br/>				
ГОСТ 18105-2010<br/>
</p>
  <p align=center >------------------------------------------------------------------------------------------------------------------------</p>
   <?php } ?>
 
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption>Результаты статистического метода контроля прочности товарного бетона "Teka" по ГОСТ 18105-2010  </caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Требуемая прочность Rt, МПа</td>
 </tr>			
 <? $l=0;
 $result = mysql_query("SELECT `Класс` FROM `excel2mysql0_tt` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ORDER BY `excel2mysql0_tt`.`Класс` ASC");				// Запрос основной таблицы
while($row = mysql_fetch_array($result)){
 extract ($row);?>
 	<tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?echo str_replace('.',',',(rtrim(rtrim($Класс,'0'), '.')))?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Rt[$l],1), 1, '.', '')))?></td>
   </tr>						
<?$l=$l+1;	}?>		
<?php 
mysql_query ("UPDATE `excel2mysql0_tt` SET `KOEF`=1");                      // записать единицу в KOEF   
?>
</table>
    </body>
</html>