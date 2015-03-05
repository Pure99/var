<div  class="pole jumbotron" >
<form  name="Form" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="2"/>
<br><input type="submit" class="btn btn-primary">
</form></div>
<div class="print">
<table class="table-autostripe table-autosort table-autofilter table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount sort01" align="center" border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table1" >
    <thead>
	<tr>
   <td class="table-filterable table-sortable:numeric table-sortable table-sorted-desc" align="center" style="width:104px; height:20px;">Дата <br>изготовления</td>			
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:122px; height:20px;">Наименование <br>изделия</td>		
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Класс <br>бетона</td>			
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Прочность, МПа</td>					
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:114px; height:20px;">Требуемая прочность, МПа</td>
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Прочность, %<br></td> 					
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Добавка<br></td>  						
   </tr>
  </thead>
<?php
$connection->query("update `base`.`excel2mysql0_k2` set `KOEF` = 0 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_k2`.`Прочность_проценты` < 100"); // Пометить строки, где прочность меньше ста процентов
$result = $connection->query("SELECT * FROM excel2mysql0_k2 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_k2`.`KOEF` = 1");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
 <tr>
<td contenteditable="true" type="date"><?php echo $row['Дата']?></td>
<td contenteditable="true" align="left"><?=$row['Наименование_изделия']?></td>
<td contenteditable="true"><?=$row['Класс_бетона']?></td>
<td contenteditable="true"><?=$row['Прочность_МПа']?></td>
<td contenteditable="true"><?=$row['Требуемая_прочность_МПа']?></td>
<td contenteditable="true"><?=$row['Прочность_проценты']?></td>
<td contenteditable="true"><?=$row['Добавка']?></td>
</tr>
  <?php }?>
  </table>
 </div>
 <br/>

<p>Официальный коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
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
 //  echo $DFR.' -Максимальная разность квадратов';               
//   echo '<br/>';
 //  echo $DFP.' -Прочность, которую нужно отсеять, если коэффициент вариации больше 8.7';                     // Прочность, которую нужно отсеять
 //  echo '<br/>';
   if ($b>6) {$Sm=sqrt($sumR/($b-1)) ;} else {$Sm=($P_max-$P_min)/alfa($b);}
  // echo $Sm.' -Среднее квадротическое отклонение';
 //  echo '<br/>';
   $Vm=$Sm*100/$mid_s;
 //  echo $Vm.' -Коэффициент вариации';
   if ($Vm > $koef_var) {
  $connection->query ("update `base`.`excel2mysql0_k2` set `KOEF` = 0  WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Прочность_МПа` = $DFP and `Наименование_изделия` like '$Наименование_изделия' ");
  }
  } while ($Vm > $koef_var);
   ?>
 <div class="print">
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
	<caption><?php echo $row['Наименование_изделия'] ; echo ' Класс B';  echo rtrim(rtrim($Класс_бетона,'0'), '.')	;?></caption>
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
  $result_3 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия' and `KOEF` like '1' "); 
  while($row_3 = $result_3->fetch_array()){ 
  extract ($row_3);
  $n=$n+1;
  ?>
  <tr>	
   <td align="center"><?php echo$n?></td>
   <td align="center"><?php echo$Дата?></td>
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
   <td align="center"><?php echo number_format(round($mid_s,1), 1, '.', '')?></td>
   <td align="center"></td>
   <td align="center"><?php echo number_format(round($sumR,1), 1, '.', '')  ?> </td>
   <td align="center"><?php echo number_format(($P_max-$P_min), 1, '.', '')   ?></td>   
   <td align="center"><?php if ($b>6) {echo number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '') ;} else {echo number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', '');}?></td>  
   <td align="center"><?php echo  number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', ''); $Mas_Var[]=$Vm ?> </td>  
   <td align="center"><?php echo $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ?></td>  
   <td align="center"><?php echo  number_format(round(($Rt=$row['Класс_бетона'] *  $Kt),1), 1, '.', ''); $Mas_Rt[]=$Rt ?> </td>  
  </tr>
  </table>
  <br/>
   <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
 <?php $result_4 = $connection->query("SELECT `Дата`, `Прочность_МПа` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия' and `KOEF` like '0' "); 
  while($row_4 = $result_4->fetch_array()){ 
  extract ($row_4);?>
  <tr>
   <td align="center"><?echo$Дата?></td>
   <td align="center"><?echo$Прочность_МПа?></td>
  </tr>
  <?php } ?>
   </table>
 <br/>
 <p>
  Rm ≥ Rт		<?=$mid_s;?> МПа > <?=$Rt;?>	<br/>
Rmin = <?=$Rt-4?> МПа						<br/>
B˂Rmini≥Rmin			<?=$Класс_бетона?>˂<?=$P_min?>˃<?=$Rt-4?>			<br/>
Заключение: Партия бетона подлежит приемке в соответствии с требованиями		<br/>				
ГОСТ 18105-2010								<br/>
</p>
<p align=center >------------------------------------------------------------------------------------------------------------------------</p>
</div>
   <?php  }
   ?>
<div class="print">
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
 $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k2` where `KOEF` like '1' and DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k2`.`Класс_бетона` ASC");				// Запрос основной таблицы
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
    </div>
