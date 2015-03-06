<h3>Товарный бетон</h3><div class="pole jumbotron">
<form name="Form" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
Коэффициет вариации:<input style="width:172px" type="text" name="koef_var" class="form-control" value="<?=$koef_var?>">
<input type="hidden" name="viewInfo" value="5"/>
<br><input type="submit" class="btn btn-primary">
</form></div>
<div class="print">
<table style="margin-left:200px" class="table-autostripe table-autosort table-autofilter table-stripeclass:alternate table-page-number:t1page table-page-count:t1pages table-filtered-rowcount:t1filtercount table-rowcount:t1allcount sort01" align="center" border="1px" cellpadding="0px" cellspacing="0px" id="table1" >
    <thead>
    <tr>
   <td class="table-filterable table-sortable:numeric table-sortable table-sorted-desc" align="center" style="width:94px; height:20px;">Дата <br/>изготовления</td>					
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:122px; height:20px;">Класс <br/>бетона</td>				
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:60px; height:20px;">БСЦ/РБУ<br></td>						
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:90px; height:20px;">Прочность <br/>7 суток, МПа</td>							
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:95px; height:20px;">Прочность <br/>28 суток, МПа</td>			
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:110px; height:20px;">Требуемая <br/>Прочность, МПа</td>  
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:80px; height:20px;">Прочность <br/>7 суток, %</td>	
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:80px; height:20px;">Прочность <br/>28 суток, %</td>	
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:80px; height:20px;">Прирост<br></td>
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:80px; height:20px;">Место <br/>отгрузки БС</td>
   <td class="table-filterable table-sortable:default table-sortable" align="center" align="center" style="width:80px; height:20px;">Добавка<br></td>							
  </tr>
   </thead>
<?php
$connection->query("update `base`.`excel2mysql0_t2` set `KOEF` = 0 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_t2`.`Прочность_28_проценты` < 100"); 

 $result = $connection->query("SELECT * FROM excel2mysql0_t2 WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_t2`.`KOEF` = 1");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr >
<td contenteditable="true"><?php echo $row['Дата']?></td>
<td contenteditable="true" align="left"><?=$row['Класс']?></td>
<td contenteditable="true"><?=$row['БСЦ_РБУ']?></td>
<td contenteditable="true"><?=$row['Прочность7']?></td>
<td contenteditable="true"><?=$row['Прочность28']?></td>
<td contenteditable="true"><?=$row['Требуемая_прочность_МПа']?></td>
<td contenteditable="true"><?=$row['Прочность_7_проценты']?></td>
<td contenteditable="true"><?=$row['Прочность_28_проценты']?></td>
<td contenteditable="true"><?=$row['Прирост']?></td>
<td contenteditable="true"><?=$row['Место_отгрузки_БС']?></td>
<td contenteditable="true"><?=$row['Добавка']?></td>
</tr>
  <?php }?>
  </table>
 </div>
<br/>
<p>Официальный коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT  `Класс`,`Дата` FROM `excel2mysql0_t2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ASC");
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   do {
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_t2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'");  
  
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
   $result_2 = $connection->query("SELECT `Дата`,  `Прочность28`,`Прочность_28_проценты` FROM `excel2mysql0_t2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'"); 
   
 while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность28-$mid_s)*($Прочность28-$mid_s);
   if   (($Прочность28-$mid_s)*($Прочность28-$mid_s) >= $DFR) {$DFR=($Прочность28-$mid_s)*($Прочность28-$mid_s); $DFP=$Прочность28; }
   } 
  // echo $DFR.' -Максимальная разность квадратов';               
   //echo '<br/>';
   //echo $DFP.' -Прочность, которую нужно отсеять, если коэффициент вариации больше 8.7';                     // Прочность, которую нужно отсеять
  // echo '<br/>';
   if ($b>6) {$Sm=sqrt($sumR/($b-1)) ;} else {$Sm=($P_max-$P_min)/alfa($b);}
  // echo $Sm.' -Среднее квадротическое отклонение';
   //echo '<br/>';
   $Vm=$Sm*100/$mid_s;
  // echo $Vm.' -Коэффициент вариации';
   if ($Vm > $koef_var) {
  $connection->query ("update `base`.`excel2mysql0_t2` set `KOEF` = 0  WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `excel2mysql0_t2`.`Прочность28` = $DFP and `Класс` like '$Класс' ");
  }
  } while ($Vm > $koef_var);?>
   <div class="print">
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
  $result_3 = $connection->query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_t2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '1'"); 
  while($row_3 = $result_3->fetch_array()){ 
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
   <td align="center"><?php echo number_format(round($mid_s,1), 1, '.', '')?></td>
   <td align="center"></td>
   <td align="center"><?php echo number_format(round($sumR,1), 1, '.', '')  ?> </td>
   <td align="center"><?php echo number_format(($P_max-$P_min), 1, '.', '')    ?></td>   
   <td align="center"><?php if ($b>6) {echo number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '') ;} else {echo number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', '');}?></td>  
   <td align="center"><?php  echo  number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', '') ; $Mas_Var[]=$Vm ?> </td>  
   <td align="center"><?php echo $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ?></td>  
  <td align="center"><?php  preg_match("/В(.*?)(П|С|\s)/", str_replace(',','.',$Класс), $matches);  echo $Rt=$matches[1]*$Kt; $Mas_Rt[]=$Rt ?> </td>  
  </tr>
  </table>
  <br/>
  <table border="1px" align=center bgcolor=#eaeae cellpadding="0px" cellspacing="0px" id="table2">
 <?php $result_4 = $connection->query("SELECT `Дата`,  `Прочность28` FROM `excel2mysql0_t2` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс' and `KOEF` like '0'"); 
  while($row_4 = $result_4->fetch_array()){ 
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
В˂Rmini≥Rmin	<?=$matches[1]?>˂<?=$P_min?>˃<?=$Rt-4?><br/>
Заключение: Партия бетона подлежит приемке в соответствии с требованиями<br/>				
ГОСТ 18105-2010<br/>
</p>
<p align=center >------------------------------------------------------------------------------------------------------------------------</p>
</div>
   <?php }?>
  <div class="print">
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption>Результаты статистического метода контроля прочности товарного бетона по ГОСТ 18105-2010    </caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Требуемая прочность Rt, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Класс` FROM `excel2mysql0_t2` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `KOEF` like '1' GROUP BY `Класс` ORDER BY `excel2mysql0_t2`.`Класс` ASC");				// Запрос основной таблицы
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
$connection->query ("UPDATE `excel2mysql0_t2` SET `KOEF`=1");                      // записать единицу в KOEF   
?>
</table>
 </div>
