<?php
session_start();
if (isset($_GET['action']) AND $_GET['action']=="logout") {
  session_destroy();
  header("Location: http://".$_SERVER['HTTP_HOST']."/var");
  echo 'logout';
  exit ('выход');
}
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
if (isset($_GET['viewInfo']) and $_GET['viewInfo'] == '1') 
		require ('var/Konst/fact_K.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '2') 
		require ('var/Konst/ofsl_K.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '3') {
	 	require  ('auth.php');
	 	require ('var/Konst/xls.php');}
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '4') 
		require ('var/Tov/fact_T.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '5') 
		require ('var/Tov/ofsl_T.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '6') {
		require  ('auth.php');
		require ('var/Tov/xls.php');}
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '7') 
		require ('var/Tov_T/fact_TT.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '8') 
		require ('var/Tov_T/ofsl_TT.php');
	 elseif (isset($_GET['viewInfo']) and$_GET['viewInfo'] == '9') {
		require  ('auth.php');
		require ('var/Tov_T/xls.php');}
else{
?>
<div class="pole jumbotron " >
<form  name="Form" method="GET" action="<?=$_SERVER['PHP_SELF']?>">
Начало периода:<input type="DATE" name="data1" class="form-control" value="<?=$data1?>">
Конец периода:<input type="DATE" name="data2" class="form-control" value="<?=$data2?>">
<br><input type="submit" class="btn btn-primary"></form></div>
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
