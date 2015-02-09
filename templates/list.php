<?php
session_start();
require ('header.php');
require ('config.php');
if (isset($_GET['data1'])) 
		$data1=$_SESSION['data1']=$_GET['data1']  ;
elseif (isset($_SESSION['data1'])) 
		$data1=$_SESSION['data1'] ;
else $data1=date("Y-m-d",strtotime("first day of -2 month"));
if (isset($_GET['data2'])) 
		$data2=$_SESSION['data2']=$_GET['data2']  ;
elseif (isset($_SESSION['data2'])) 
		$data2=$_SESSION['data2'] ;
else $data2=date("Y-m-d",strtotime("last day of -2 month"));
     $koef_var=isset($_GET['koef_var']) ? $_GET['koef_var'] : 8.7;
?>
<div class="container">
      <div class="starter-template">
<?php
@$viewInfo = $_GET['viewInfo'];
if ($viewInfo == '1') 
		require ('var/Konst/fact_K.php');
	 elseif ($viewInfo == '2') 
		require ('var/Konst/ofsl_K.php');
	 elseif ($viewInfo == '3') 
		require ('var/Konst/xls.php');
	 elseif ($viewInfo == '4') 
		require ('var/Tov/fact_T.php');
	 elseif ($viewInfo == '5') 
		require ('var/Tov/ofsl_T.php');
	 elseif ($viewInfo == '6') 
		require ('var/Tov/xls.php');
	 elseif ($viewInfo == '7') 
		require ('var/Tov_T/fact_TT.php');
	 elseif ($viewInfo == '8') 
		require ('var/Tov_T/ofsl_TT.php');
	 elseif ($viewInfo == '9') 
		require ('var/Tov_T/xls.php');
else{
?>
<form name="authForm" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" value="<?=$data2?>">
<input type="submit"></form>
<?php
require ('calc/fact_K.php');
require ('calc/ofsl_K.php');
require ('calc/fact_T.php');
require ('calc/ofsl_T.php');
require ('calc/fact_TT.php');
require ('calc/ofsl_TT.php');
}?> 
      </div>
</div>
<?php require ('footer.php');?>
