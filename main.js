$(document).ready(function() {
    //toggle `popup` / `inline` mode
    $.fn.editable.defaults.mode = 'popup';     
    
    //make username editable
    $('.izdelie').editable();
    $('.klass_betona').editable();
    $('.prochnost').editable();
    $('.tr_prochnost').editable();
    $('.class').editable();
    $(document).on('click','.editable-submit',function(){
    			var AReplaceText =''; 
    			var VRegExp = new RegExp(/\s.*/);
    			var a = $(this).closest('td').children('span').attr('class').replace(VRegExp, AReplaceText);
			var b = $(this).closest('td').children('span').attr('id');
			var c = $('.input-sm').val();
			var VRegExp = new RegExp(/\s.*?\s/);
			var x = $.trim($(this).closest('td').children('span').attr('class').match(VRegExp));
			var z = $(this).closest('td').children('span');
			$.ajax({
				url: "../var/process.php?table="+a+"&id="+b+"&"+x+"="+c,
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
    $(document).on('onmouseover',function(){
       
var b = $(this).closest('td').children('input').attr('id');
alert (b);

});

});
