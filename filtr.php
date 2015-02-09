<!DOCTYPE HTML>
<html>
<head>
  <title>Untitled</title>
  <meta charset="utf-8">
 
  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
  <script>
$(function () {
    $("table").tablework();
});
$.fn.tablework = function() {
    return this.each(function() {
        var $table = $(this),
            $thead = $table.find('thead');
        !$thead.length && ($thead = $('<thead/>').prependTo($table));
        var $tr = $('<tr/>').prependTo($thead),
            hide = {};
        $("tbody tr:first>", $table).each(function(indx, element) {
            var range = ['Без выбора'],
                $td = $("tbody tr :nth-of-type(" + (indx + 1) + ")", $table),
                temp = {};
            $td.each(function(i, el) {
                var text = $(this).text()
                if (!temp[text]) {
                    range.unshift(text);
                    temp[text] = true
                };
            });

            var $select = $('<select/>', {
                'change': function() {
                    var val = this.value;
                    hide[indx] = [];
                    this.selectedIndex && $td.each(function(i, el) {
                        $(this).text() != val && hide[indx].push(i)
                    });
                    var range = [];
                    for (var k in hide) range = range.concat(hide[k]);

                    var $tr = $("tbody tr", $table);
                    $tr.show();
                    $.each(range, function(i, el) {
                        $tr.eq(el).hide()
                    });
                }

            });
            $.each(range, function() {
                $('<option/>', {
                    text: this
                }).prependTo($select);
            });
            $('<td/>').append($select).appendTo($tr);
        });

    });
};
</script>
</head>
<body>
<br>
<table class="zebra git" >

        <tbody>
            <tr class="t_head" id="1">
			<th style="width: 70px;">Период</th>
			<th style="width: 15px;">test</th>
			<th>DBL</th>
			<th>SGL</th>

		</tr>

            <tr>
				<td>Date</td>
				<td>Start time</td>
				<td>End time</td>
				<td>Name</td>
            </tr>
        	<tr>
            	<td>02.06.2010</td>
                <td>10:00</td>
                <td>12:00</td>
                <td>Cleaning</td>
            </tr>
            <tr>
            	<td>02.06.2010</td>
                <td>12:00</td>
                <td>15:00</td>
                <td>Training</td>
            </tr>
            <tr>
            	<td>02.06.2010</td>
                <td>15:00</td>
                <td>17:00</td>
                <td>Rest</td>
            </tr>
            <tr>
            	<td>02.06.2010</td>
                <td>17:00</td>
                <td>21:00</td>
                <td>Work</td>
            </tr>
            <tr>
            	<td>02.06.2010</td>
                <td>21:00</td>
                <td>07:00</td>
                <td>Sleep</td>
            </tr>
        </tbody>
	</table>

</body>
</html>

