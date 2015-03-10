<?php
include("connect.php");
?>
<!DOCTYPE html>
<html>
<head>
    <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
    <meta charset="utf-8">
    <title>Editable Tables using jQuery - jQuery AJAX PHP</title>   
   <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="../bootstrap/css/bootstrap-theme.css" rel="stylesheet">
<link href="../bootstrap/bootstrap3-editable-1.5.1/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet">
</head>
<body>
<div id="demo-header"></div>
    <div class="container">    
		<div style="text-align:center;width:100%;font-size:24px;margin-bottom:20px;color: #2875BB;">Click on the underlined words to edit them</div>
                <div class="row">
                    <table class= "table table-striped table-bordered table-hover">
                        <thead>
                            <tr>

                                <th colspan="1" rowspan="1" style="width: 180px;" tabindex="0">Name</th>

                                <th colspan="1" rowspan="1" style="width: 220px;" tabindex="0">Description</th>

                                <th colspan="1" rowspan="1" style="width: 288px;" tabindex="0">Status</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
				$result = $connection->query("select * from information");
$i=0;
while($row = $result->fetch_array()){
					if($i%2==0) $class = 'even'; else $class = 'odd';
							
					echo'<tr class="'.$class.'">

                                <td><span class= "xedit" id="'.$row['id'].'">'.$row['name'].'</span></td>

                                <td><span class= "xedit" id="'.$row['id'].'">'.$row['details'].'</span></td>

                                <td>'.$row['status'].'</td>
                            </tr>';							
						}
						?>
                        </tbody>
                    </table>
        </div>
		
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
		<script src="../bootstrap/js/bootstrap.min.js"></script>
        <script src="../bootstrap/bootstrap3-editable-1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script type="text/javascript">
jQuery(document).ready(function() {  
        $.fn.editable.defaults.mode = 'popup';
        $('.xedit').editable();		
		$(document).on('click','.editable-submit',function(){
			var x = $(this).closest('td').children('span').attr('id');
			var y = $('.input-sm').val();
			var z = $(this).closest('td').children('span');
			$.ajax({
				url: "process.php?id="+x+"&details="+y+"&data="+y,
				type: 'GET',
				success: function(s){
					if(s == 'status'){
					$(z).html(y);}
					if(s == 'error') {
					alert('Error Processing your Request!');}
				},
				error: function(e){
					alert('Error Processing your Request!!');
				}
			});
		});
});
</script>
    </div>
</body>
</html>