<?php // конструкционный официальный
$connection->query("update `base`.`excel2mysql0_k2` set `KOEF` = 0 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_k2`.`Прочность_проценты` < 100"); // Пометить строки, где прочность меньше ста процентов
  // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k2` where `KOEF` like '1' and DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `Класс_бетона`ASC");
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   do {
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа`,`Прочность_проценты` FROM `excel2mysql0_k2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия' and `KOEF` like '1'");  
  while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность_МПа;
  if   ($Прочность_МПа > $P_max) $P_max=$Прочность_МПа;        // определение максимального значения
  if   ($Прочность_МПа < $P_min) $P_min=$Прочность_МПа;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0;
   $DFR=0;       // Максимальная разность квадратов
   $DFP=0;
   $result_2 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` ,`Прочность_проценты` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия' and `KOEF` like '1'"); 
   
 while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s);
   if   (($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s) > $DFR) {$DFR=($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s); $DFP=$Прочность_МПа; }
   } 
   if ($b>6) {$Sm=sqrt($sumR/($b-1)) ;} else {$Sm=($P_max-$P_min)/alfa($b);}
   $Vm=$Sm*100/$mid_s;
   if ($Vm > $koef_var) {
  $connection->query ("update `base`.`excel2mysql0_k2` set `KOEF` = 0  WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Прочность_МПа` = $DFP and `Наименование_изделия` like '$Наименование_изделия' ");
  }
  } while ($Vm > $koef_var);
    $Mas_Var[]=$Vm ;
     $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ;
    $Rt=$row['Класс_бетона'] *  $Kt; $Mas_Rt[]=$Rt ;
     }
   ?>
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption>Результаты статистического метода контроля прочности конструкционного бетона на 28 суток по ГОСТ 18105-2010 </caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Наименование изделия</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Требуемая прочность Rt, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k2` where `KOEF` like '1' and DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k2`.`Класс_бетона` ASC");	// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
 		<tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?=$Наименование_изделия?></td>
   <td align="center"><?echo str_replace('.',',',(rtrim(rtrim($Класс_бетона,'0'), '.')))?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Rt[$l],1), 1, '.', '')))?></td>
 </tr>			
<?$l=$l+1;	}?>
<?php 
$connection->query ("UPDATE `excel2mysql0_k2` SET `KOEF`=1");                      // записать единицу в KOEF   
?>
</table>
<?php unset($Mas_Var);
unset($Mas_Rt); ?>
