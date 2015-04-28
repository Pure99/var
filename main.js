$(function edit() {            //функция ввода значений
    $.fn.editable.defaults.mode = 'popup';   //toggle `popup` / `inline` mode   
    $('.data').editable();
    $('.izdelie').editable();
    $('.klass_betona').editable();
    $('.prochnost').editable();
    $('.tr_prochnost').editable();
    $('.class').editable();
    $(document).on('click','.editable-submit',function(){
    		var AReplaceText =''; 
    		var VRegExp = new RegExp(/\s.*/);
    		var a = $(this).closest('td').children('span').attr('class').replace(VRegExp, AReplaceText); // определяется класс-название таблицы
			var b = $(this).closest('td').children('span').attr('id');  //определяется id строки
			var c = $('.input-sm').val();          //определяется введенное значение
			var VRegExp = new RegExp(/\s.*?\s/);
			var x = $.trim($(this).closest('td').children('span').attr('class').match(VRegExp)); //определяется класс-название столбца 
			alert (a);
			alert (x);
			var z = $(this).closest('td').children('span');
			if (x == 'prochnost') {
alert (c);
alert (c/$(this).closest('tr').children('td').children('span.tr_prochnost').text()*100);
$(this).closest('tr').children('td.proc').html(Math.round(c/$(this).closest('tr').children('td').children('span.tr_prochnost').text()*100).toFixed(0));
			}
			if (x == 'tr_prochnost') {
alert (c);
alert ($(this).closest('tr').children('td').children('span.prochnost').text()/c*100);
$(this).closest('tr').children('td.proc').html(Math.round($(this).closest('tr').children('td').children('span.prochnost').text()/c*100).toFixed(0));
}		     
if (x == 'data'){
	//var c = $(document).on('.editable-unsaved').val();
/*$(function() {
    $('.data').daterangepicker({
        singleDatePicker: true,
        showDropdowns: true
    }, 
    function(start, end, label) {
        var years = moment().diff(start, 'years');
        alert("You are " + years + " years old.");
    });
});
alert (c);
*/
}
			$.ajax({
				url: "../var/process.php?table="+a+"&id="+b+"&"+x+"="+c,
				type: 'GET',
				success: function(s){
					if(s == 'status'){  }
					if(s == 'error') {
					alert('Error Processing your Request!');}
				},
				error: function(e){
					alert('Error Processing your Request!!');
				}
			});
		});
});
$(function() {                      //функция добавления строки 
$(".add").click ( function() {
var commentId = $(this).attr("id");
$.post("add.php",{table : commentId},AjaxSuccess);
function AjaxSuccess(data) {
if (data) {
          switch (data) {
           case 'ERROR1' :
            alert('Полученны не верный параметр id!');
           break;
           case 'ERROR2' :
            alert('Не добавлено!');
           break;
           default:
			alert(data);
			$(data).insertBefore($("tr:last"));
			$('.data').editable();
			$('.izdelie').editable();
			$('.klass_betona').editable();
			$('.prochnost').editable();
			$('.tr_prochnost').editable();
			$('.class').editable();
          }
         }
         else alert('Ошибка передачи данных!');
    }
});
});
 $(document).on('click','.delete',function(){   // функция удаления строки
    var AReplaceText ='';
    var VRegExp = new RegExp(/\s.*/);
    var table = $(this).closest('tr').children('td').children('span').attr('class').replace(VRegExp, AReplaceText);  
 if (confirm('Вы действительно хотите удалить строку???')){
alert (table);              
    var commentContainer = $(this).parent(); 
    var commentId = $(this).attr("id");// получаем значение элемента
   $.post("delete.php",{id : commentId, table : table},AjaxSuccess);// Отправляем Ajax запрос методом POST, переменную id со значением commentId
    function AjaxSuccess(data)
    {
         if (data) {      // Здесь мы получаем данные, отправленные сервером и обрабатываем их
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
    return false;}
  });


