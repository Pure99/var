$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    //make username editable
    $('.izdelie').editable();
	$('.klass_betona').editable();
    $(document).on('click','.editable-submit',function(){
			var a = $(this).closest('td').children('span').attr('id');
			var b = $('.input-sm').val();
		//	var c = $('.input-sm').closest('.klass_betona').val();
			var z = $(this).closest('td').children('span');
			$.ajax({
				url: "../var/process.php?id="+a+"&izdelie="+b+"&klass_betona="+c,
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
