<?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k`.`Класс_бетона` ASC");
  
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия'");  
 
while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность_МПа;
  if   ($Прочность_МПа > $P_max) $P_max=$Прочность_МПа;        // определение максимального значения
  if   ($Прочность_МПа < $P_min) $P_min=$Прочность_МПа;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0;                      // Сумма квадратов
   $result_2 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and  `Наименование_изделия` like '$Наименование_изделия'"); 
 
 while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR + ($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s);
   } 
 
    $Mas_Mid[]=$mid_s; 
    if ($b>6) { $Sm=sqrt($sumR/($b-1)) ;
}
 else { $Sm=($P_max-$P_min)/alfa($b);
}
    $Vm=$Sm*100/$mid_s ; $Mas_Var[]=$Vm;
    
    } ?>
   
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
 $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k`.`Класс_бетона` ASC");				// Запрос основной таблицы
while($row = $result->fetch_array()){ // Сводная таблица
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
<?php unset($Mas_Var);
unset($Mas_Mid); ?>


