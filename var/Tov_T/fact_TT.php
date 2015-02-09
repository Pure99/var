<form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" value="<?=$data2?>">
<input type="hidden" name="viewInfo" value="7"/>
<input type="submit">
</form>

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
 $result = $connection->query("SELECT * FROM excel2mysql0_tt where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2'");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
  <tr >
<td ><input type="date" name="Date" onchange="alert (this.value);" value="<?php echo $row['Дата']?>" style="width:140px; height:20px; border:2px;" /></td>
<td><input type="text" name="Name" value="<?=$row['Класс']?>" style="width:130px; height:20px; border:2px"  /></td>
<td><input type="text" name="Strong_MPa" value="<?=$row['Прочность7']?>" style="width:120px; height:20px; border:2px;text-align:center"/></td>
<td><input type="text" name="Strong_MPa_Tr" value="<?=$row['Прочность28']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Strong_MPa_P" value="<?=$row['Требуемая_прочность_МПа']?>" style="width:120px; height:20px; border:2px;text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_7_проценты']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прочность_28_проценты']?>" style="width:110px; height:20px; border:2px; text-align:center"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Прирост']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Место_отгрузки_БС']?>" style="width:110px; height:20px; border:2px"   /></td>
<td><input type="text" name="Dobavka" value="<?=$row['Добавка']?>" style="width:110px; height:20px; border:2px"   /></td>

</tr>
  <?php }?>
  </table>
  <br/>
 
</p>

<p>Фактический коэффициент вариации</p>
  
  <?php // Выводим таблицу для расчета коэффициента вариации для каждого изделия
  $result = $connection->query("SELECT  `Класс`,`Дата` FROM `excel2mysql0_tt` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Класс` ASC");
  while($row = $result->fetch_array()){           // Список всех наименований изделий
   extract ($row);
   $b=0;          // количество значений прочностей 
   $sum=0;         //сумма прочностей
   $P_max=0;         //максимальная прочность
   $P_min=100;      //минимальная прочность
   $result_1 = $connection->query("SELECT `Дата`,`Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс'");  
  while($row_1 = $result_1->fetch_array()){ // Этот цикл вычисляет сумму прочностей, минимальное и максимальное значение прочности
   extract ($row_1);
   $sum = $sum+$Прочность28;
  if   ($Прочность28 > $P_max) $P_max=$Прочность28;        // определение максимального значения
  if   ($Прочность28 < $P_min) $P_min=$Прочность28;        // определен  минимального значения
   $b=$b+1;
  } 
   $mid_s=$sum/$b;               // средняя фактическая прочность
   $sumR=0;
   
   $result_2 = $connection->query("SELECT `Дата`,`Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс'"); 
  while($row_2 = $result_2->fetch_array()){ //этот цикл вычисляет сумму квадратов 
   extract ($row_2);
   $sumR=$sumR +  ($Прочность28-$mid_s)*($Прочность28-$mid_s);
  
   } ?>
 
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
  $result_3 = $connection->query("SELECT `Дата`,   `Прочность28` FROM `excel2mysql0_tt` WHERE DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' and `Класс` like '$Класс'"); 
  while($row_3 = $result_3->fetch_array()){ 
  extract ($row_3);
  $n=$n+1;
  ?>
  <tr>	
   <td align="center"><?php echo $n?></td>
   <td align="center"><?php echo $Дата?></td>
   <td align="center"><?php echo $Прочность28?></td>
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
   <td align="center"><?php echo round($mid_s,1); $Mas_Mid[]=$mid_s;?></td>
   <td align="center"></td>
   <td align="center"><?php echo round($sumR,1)  ?> </td>
   <td align="center"><?php echo $P_max-$P_min     ?></td>   
   <td align="center"><?php if ($b>6) {echo number_format(round($Sm=sqrt($sumR/($b-1)),1), 1, '.', '') ;} else {echo number_format(round($Sm=($P_max-$P_min)/alfa($b),1), 1, '.', '');}?></td>  
   <td align="center"><?php echo  number_format(round($Vm=$Sm*100/$mid_s,1), 1, '.', '') ;$Mas_Var[]=$Vm;?> </td>  
   <td align="center"><?php echo $Kt=number_format(round(interpol($Vm),2), 2, '.', '') ?></td>  
   <td align="center"><?php echo preg_replace("/В(.*?)(П|С|\s)/",  "\${1}\$", str_replace(',','.',$row['Класс']))  *  $Kt   ?> </td>  
  </tr>
  </table>
 <br/>
 <p>
 Rm ≥ Rт <br/>		
Rmin = <br/>
В˂Rmini≥Rmin <br/>
</p>
<p align=center >------------------------------------------------------------------------------------------------------------------------</p>
   <?php } ?>
  
 <!-- выводим сводную таблицу-->
 <table border="1px" align=center bgcolor=#eaeaea cellpadding="4px" cellspacing="0px" id="table3">
	<caption contenteditable="true">Результаты статистического метода контроля прочности товарного бетона "ТЕКА" по ГОСТ 18105-2010  фактический</caption>
  <tr>
   <td align="center">N</td>
   <td align="center">Класс бетона</td>
   <td align="center">Коэффициент вариации Vm, %</td>
   <td align="center">Средняя прочность Rm, МПа</td>
   <td align="center">Прочность по ГОСТ, МПа</td>
 </tr>			
 <? $l=0;
 $result = $connection->query("SELECT `Класс` FROM `excel2mysql0_tt` where DATE(`Дата`) >= '$data1' AND DATE(`Дата`) <= '$data2' GROUP BY `Класс`");				// Запрос основной таблицы
while($row = $result->fetch_array()){
 extract ($row);?>
 		<tr>
   <td align="center"><?=$l+1?></td>
   <td align="center"><?=$Класс?></td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Var[$l],1), 1, '.', '')))?> </td>
   <td align="center"><?=str_replace('.',',',(number_format(round($Mas_Mid[$l],1), 1, '.', '')))?></td>
   <td align="center"><? preg_match("/В(.*?)(П|С|\s)/", $Класс, $matches);    echo str_replace('.',',',(number_format(round($matches[1]*1.31,1), 1, '.', '')))?></td>
 </tr>						
<?$l=$l+1;	}?>
					
</table>	
  
   



