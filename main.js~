$(function edit() {
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
			if (x == 'prochnost') {
			alert (c);
			alert (c/$(this).closest('tr').children('td').children('span.tr_prochnost').text()*100);
			$(this).closest('tr').children('td.proc').html(Math.round(c/$(this).closest('tr').children('td').children('span.tr_prochnost').text()*100).toFixed(0));
	}
			if (x == 'tr_prochnost') {
			alert (c);
alert ($(this).closest('tr').children('td').children('span.prochnost').text()/c*100);
}		     
			$.ajax({
				url: "../var/process.php?table="+a+"&id="+b+"&"+x+"="+c,
				type: 'GET',
				success: function(s){
					if(s == 'status'){
					//$(z).html(y);
					//alert (z);
							}
					if(s == 'error') {
					alert('Error Processing your Request!');}
				},
				error: function(e){
					alert('Error Processing your Request!!');
				}
			});
		});
    

});

// скрываем индикатор обработки данных
//$(document).ready(
//  function() {
   //$('#load').hide();
//  }
//);
// перехватываем значение id элемента с классом delete
// с помощью ajax, отправляем значение медодом POST файлу delete.php
$(function() {
  $(".delete").click ( function() {
    //$('#load').fadeIn();
    var commentContainer = $(this).parent();
    // получаем значение элемента
    var commentId = $(this).attr("id");
   
    // Отправляем Ajax запрос методом POST, переменную id со значением commentId
   $.post("delete.php",{id : commentId},AjaxSuccess);
    function AjaxSuccess(data)
    {
         // скрываем индикатор обработки данных
        // $('#load').fadeOut();
         // Здесь мы получаем данные, отправленные сервером и обрабатываем их
         if (data) {
          switch (data) {
           case 'ERROR1' :
            alert('Полученны не верный параметр id!');
           break;
           case 'ERROR2' :
            alert('Не удалено!');
           break;
           default:
	   alert(data);
           commentContainer.remove();
          }
         }
         else alert('Ошибка передачи данных!');
    }
   
    return false;
   });

});

$(function() {
$(".add").click ( function() {
//var commentContainer = $(this).parent();
var commentId = $(this).attr("id");
$.post("add.php",{table : commentId},AjaxSuccess);
 
function AjaxSuccess(data)
    {
if (data) {
          switch (data) {
           case 'ERROR1' :
            alert('Полученны не верный параметр id!');
           break;
           case 'ERROR2' :
            alert('Не удалено!');
           break;
           default:
	   alert(data);
          // $(commentContainer).insertBefore($("tr:last"));
           $(data).insertBefore($("tr:last"));
          $(function edit() {
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
					//alert ('fff');
					if(s == 'status'){
					//$(z).html(y);
					//alert (z);
							}
					if(s == 'error') {
					alert('Error Processing your Request!');}
				},
				error: function(e){
					alert('Error Processing your Request!!');
				}
			});
		});
    

});
$(function() {
  $(".delete").click ( function() {
    //$('#load').fadeIn();
    var commentContainer = $(this).parent();
    // получаем значение элемента
    var commentId = $(this).attr("id");
   
    // Отправляем Ajax запрос методом POST, переменную id со значением commentId
   $.post("delete.php",{id : commentId},AjaxSuccess);
    function AjaxSuccess(data)
    {
         // скрываем индикатор обработки данных
        // $('#load').fadeOut();
         // Здесь мы получаем данные, отправленные сервером и обрабатываем их
         if (data) {
          switch (data) {
           case 'ERROR1' :
            alert('Полученны не верный параметр id!');
           break;
           case 'ERROR2' :
            alert('Не удалено!');
           break;
           default:
	   alert(data);
           commentContainer.remove();
          }
         }
         else alert('Ошибка передачи данных!');
    }
   
    return false;
   });

});
          }
         }
         else alert('Ошибка передачи данных!');
    }
});
});


