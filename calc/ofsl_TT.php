<?php //товарный тека официальный 
$connection->query("update `base`.`excel2mysql0_tt2` set `KOEF` = 0 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_tt2`.`Прочность_28_проценты` < 100"); 
// Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT  `Класс`,`Дата` FROM `excel2mysql0_tt2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ASC");
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   do {
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_tt2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'");  
  while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
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
   $result_2 = $connection->query("SELECT `Дата`,  `Прочность28`,`Прочность_28_проценты` FROM `excel2mysql0_tt2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'"); 
 while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность28-$mid_s)*($Прочность28-$mid_s);
   if   (($Прочность28-$mid_s)*($Прочность28-$mid_s) > $DFR) {$DFR=($Прочность28-$mid_s)*($Прочность28-$mid_s); $DFP=$Прочность28; }
   } 
   if ($b>6) {$Sm=sqrt($sumR/($b-1)) ;} else {$Sm=($P_max-$P_min)/alfa($b);}
   $Vm=$Sm*100/$mid_s;
   if ($Vm > $koef_var) {
  $connection->query ("update `base`.`excel2mysql0_tt2` set `KOEF` = 0  WHERE `excel2mysql0_tt2`.`Прочность28` = $DFP and `Класс` like '$Класс' ");
  }
  } while ($Vm > $koef_var);
   $Mas_Var[]=$Vm ;
   $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ;
   preg_match("/(B|В)(.*?)(П|С|\s)/", str_replace(',','.',$Класс), $matches); 
   $Rt=$matches[2]*$Kt; $Mas_Rt[]=$Rt ;
    } ?>
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption>Результаты статистического метода контроля прочности товарного бетона "Teka" по ГОСТ 18105-2010  </caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Требуемая прочность Rt, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Класс` FROM `excel2mysql0_tt2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ORDER BY `excel2mysql0_tt2`.`Класс` ASC");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
 	<tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?echo str_replace('.',',',(rtrim(rtrim($Класс,'0'), '.')))?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Rt[$l],1), 1, '.', '')))?></td>
   </tr>						
<?$l=$l+1;	}?>		
<?php 
$connection->query ("UPDATE `excel2mysql0_tt2` SET `KOEF`=1");                      // записать единицу в KOEF   
?>
</table>
<?php unset($Mas_Var);
unset($Mas_Mid); 
unset($Mas_Rt); ?>
