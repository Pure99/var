 <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <script>
$(function () {
    $(".table_XLS").tablework();
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
//$('.t_head').hide()
</script>
 </body>
</html>
