<?php   // товарный фактический
 // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT  `Класс`,`Дата` FROM `excel2mysql0_t2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Класс` ASC");
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`,`Прочность28` FROM `excel2mysql0_t2` WHERE  DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс'");  
  while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность28;
  if   ($Прочность28 > $P_max) $P_max=$Прочность28;        // определение максимального значения
  if   ($Прочность28 < $P_min) $P_min=$Прочность28;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0;
   $result_2 = $connection->query("SELECT `Дата`,   `Прочность28` FROM `excel2mysql0_t2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс'"); 
  while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR + ($Прочность28-$mid_s)*($Прочность28-$mid_s);
   } 
    $Mas_Mid[]=$mid_s;
 if ($b>6)
 { $Sm=sqrt($sumR/($b-1)) ;
} else
 { $Sm=($P_max-$P_min)/alfa($b);
}
    $Vm=$Sm*100/$mid_s ;
    $Mas_Var[]=$Vm; 
    }?>
  <!-- выводим сводную таблицу-->
<table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
<caption contenteditable="true">Результаты статистического метода контроля прочности товарного бетона по ГОСТ 18105-2010 фактический</caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Средняя прочность Rm, МПа</td>
   <td align="center">Прочность по ГОСТ, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Класс` FROM `excel2mysql0_t2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Класс`");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row); ?>
 <tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?=$Класс?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Mid[$l],1), 1, '.', '')))?></td>
    <td align="center"><?preg_match("/В(.*?)(П|С|\s)/", $Класс, $matches); echo str_replace('.',',',(number_format(round($matches[1]*1.31,1), 1, '.', '')))?></td>
 </tr>			
<?php $l=$l+1; }?>	
<tr>
<td align="center"> </td>
<td align="center"> </td>
<td align="center">Vmср=<?php $count_ziro = 0; if (isset($Mas_Var)) { foreach($Mas_Var as $key => $value) { if(!$value == 0) $count_ziro++;} echo str_replace('.',',',(number_format(round(array_sum($Mas_Var)/$count_ziro,1), 1, '.', ''))) ;}?> </td>
<td align="center"></td>
<td align="center"> </td>
</tr>	
</table>	
<?php unset($Mas_Var);
unset($Mas_Mid); ?>
