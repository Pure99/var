<h3>Конструкционный бетон</h3><div style="float:left; width:177px; height:177px;"><div class="pole jumbotron">
<form  name="Form" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="Конструкционный_Фактический"/>
<br><input type="submit" class="btn btn-primary">
</form></div></div>
<div class="print">
<table class="table-autostripe table-autosort table-autofilter table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount sort01" align="center" border="1px" cellpadding="0px" cellspacing="0px" id="table1" >
    <thead>
   <td class="table-filterable table-sortable:numeric table-sortable table-sorted-desc" align="center" style="width:104px; height:20px;">Дата <br>изготовления</td>			
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:122px; height:20px;">Наименование <br>изделия</td>		
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Класс <br>бетона</td>			
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Прочность, МПа</td>					
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:114px; height:20px;">Требуемая прочность, МПа</td>
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Прочность, %<br></td> 					
   <td class="table-filterable table-sortable:default table-sortable" align="center" style="width:104px; height:20px;">Добавка<br></td>  						
  </thead>
<?php
$result = $connection->query("SELECT * FROM excel2mysql0_k2 where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");// Запрос исходной таблицы с данными
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr>
<td><?php echo $row['Дата']?></td>
<td align="left"><?=$row['Наименование_изделия']?></td>
<td ><?echo str_replace('.',',',(rtrim(rtrim($Класс_бетона,'0'), '.')))?></td>
<td ><?=str_replace('.',',',$row['Прочность_МПа'])?></td>
<td ><?=str_replace('.',',',$row['Требуемая_прочность_МПа'])?></td>
<td <?if ($row['Прочность_проценты']<100) echo "style='color:red'";?>><?=$row['Прочность_проценты']?></td>
<td ><?=$row['Добавка']?></td>
</tr>
  <?php }?>
  </table>
</div>
  <br/>
<p>Фактический коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k2`.`Класс_бетона` ASC");
  
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Наименование_изделия` like '$Наименование_изделия'");  
 
 while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность_МПа;
  if   ($Прочность_МПа > $P_max) $P_max=$Прочность_МПа;        // определение максимального значения
  if   ($Прочность_МПа < $P_min) $P_min=$Прочность_МПа;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0; 
   $result_2 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and  `Наименование_изделия` like '$Наименование_изделия'"); 
  while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR + ($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s);
  
   } ?>
 <div class="print">
  <table border="1px" align="center" cellpadding="0px" cellspacing="0px" id="table2">
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
  $result_3 = $connection->query("SELECT `Дата`, `Наименование_изделия`,  `Прочность_МПа` FROM `excel2mysql0_k2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and  `Наименование_изделия` like '$Наименование_изделия'"); 
  while($row_3 = $result_3->fetch_array()){  // Вывод результатов расчета в таблицу
  extract ($row_3);
  $n=$n+1;
  ?>
  <tr>	
   <td align="center"><?php echo $n?></td>
   <td align="center"><?php echo $Дата?></td>
   <td align="center"><?php echo str_replace('.',',',$Прочность_МПа)?></td>
   <td align="center"></td>
   <td align="center"><?php  echo str_replace('.',',',round($Прочность_МПа-$mid_s,1)) ?></td>
   <td align="center"><?php  echo str_replace('.',',',round(($Прочность_МПа-$mid_s)*($Прочность_МПа-$mid_s),1))?> </td>   
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
   <td align="center"><?php echo str_replace('.',',',$sum) ?></td>
   <td align="center"><?php echo str_replace('.',',',round($mid_s,1)); $Mas_Mid[]=$mid_s; ?></td>
   <td align="center"></td>
   <td align="center"><?php echo str_replace('.',',',round($sumR,1))?> </td>
   <td align="center"><?php echo str_replace('.',',',$P_max-$P_min)?></td>   
   <td align="center"><?php if ($b>6) {echo str_replace('.',',',number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '')) ;} else {echo str_replace('.',',',number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', ''));}?></td>  
   <td align="center"><?php  echo  str_replace('.',',',number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', '')) ; $Mas_Var[]=$Vm;?></td>  
   <td align="center"><?php echo str_replace('.',',',$Kt=number_format(round(interpol($Vm),2), 2, '.', '')) ?></td>  
   <td align="center"><?php echo  str_replace('.',',',number_format(round($Rt=$row['Класс_бетона'] *  $Kt,1), 1, '.', ''));  ?> </td>  
  </tr>
  </table>
 <br/>
 <p>
 Rm ≥ Rт				<br/>		
Rmin = 		<?echo $Rt-4?>			<br/>
В&ltRmini≥Rmin						<br/>
					<br/>
</p>

<p align=center >------------------------------------------------------------------------------------------------------------------------</p>
 </div> 
   <?php } ?>
 
<div class="print">
 <table border="1px" align="center" cellpadding="4px" cellspacing="0px" id="table3">
	<caption>Результаты статистического метода контроля прочности конструкционного бетона на 28 суток по ГОСТ 18105-2010. Фактический. Период с <?=$data1?> по <?=$data2?>.	</caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Наименование изделия</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Средняя прочность Rm, МПа</td>
   <td align="center">Прочность по ГОСТ, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Наименование_изделия`,`Класс_бетона`,`Дата` FROM `excel2mysql0_k2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Наименование_изделия` ORDER BY `excel2mysql0_k2`.`Класс_бетона` ASC");				// Запрос основной таблицы
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
<? $l=$l+1; }?>
<tr>
<td align="center"> </td>
<td align="center"> </td>
<td align="center"> </td>
<td align="center">Vmср=<?php $count_ziro = 0; if (isset($Mas_Var)) { foreach($Mas_Var as $key => $value) { if(!$value == 0) $count_ziro++;} echo str_replace('.',',',(number_format(round(array_sum($Mas_Var)/$count_ziro,1), 1, '.', ''))) ;}?></td>
<td align="center"> </td>
<td align="center"> </td>
		</tr>
</table>	
 </div><?php unset($Mas_Var); unset($Mas_Mid); ?>
