$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    //make username editable
    $('.izdelie').editable();
    $(document).on('click','.editable-submit',function(){
			var x = $(this).closest('td').children('a').attr('id');
			var y = $('.input-sm').val();
			var z = $(this).closest('td').children('span');
			$.ajax({
				url: "../var/process.php?id="+x+"&data="+y,
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
