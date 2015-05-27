<?php
$connection = new mysqli('localhost', 'root', '', 'test'); // устанавливаем соединение с БД
if (mysqli_connect_errno()) {
    printf("Ошибка подключения к базе данных: %s\n", mysqli_connect_error());
    exit();
}
	final class init {
	private function create() {
	global $connection;
	$connection->query( "CREATE TABLE `test` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `script_name` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `result` text COLLATE 'utf8_unicode_ci' NOT NULL
) COLLATE 'utf8_unicode_ci';"); //создать таблицу
	}
	private function fill() {
	global $connection;
        $result = array ('normal', 'illegal', 'failed', 'success');
	for ($k=1; $k<11; $k++){
        $x=mt_rand(0, 3);
	$connection->query("INSERT INTO `test` (`script_name`, `start_time`, `end_time`, `result`) VALUES (ROUND((RAND() * (1000 - 0)) + 0), ROUND((RAND() * (1000 - 0)) + 0), ROUND((RAND() * (1000 - 0)) + 0), '');");  // записать 
        $connection->query("UPDATE `test` SET `result`='$result[$x]' where `id`='$k';");
		}
	}
		function __construct() {
			$this->create();
			$this->fill();
		}
	public function get(){
	global $connection;
	$result = $connection->query("select * from test where result='normal' or result='success';");
echo "<table>";
	while($row = $result->fetch_object()){
echo "<tr><td>".$row->id."</td><td>".$row->script_name."</td><td>".$row->start_time."</td><td>".$row->end_time."</td><td>".$row->result."</td></tr>";
}
echo "</table>";
}
	}
	$test = new init;
        $test->get();
?>
<?php
if ($handle = opendir('./datafiles/')) {
    echo "Дескриптор каталога: $handle<br>";
    echo "Файлы:<br>";

    /* Именно этот способ чтения элементов каталога является правильным. */
    while (false !== ($file = readdir($handle))) { 
	if ($file != "." && $file != ".." &&  preg_match("/\.(:?ixt)$/i", $file, $matches) && ! preg_match("/[^a-z0-9\.]+/", $file, $matches)) {
        echo "$file<br>";
	}
    }
closedir($handle); 
}
?>
<?php
function newFormatDate($date) {
    $date = str_replace(array('янв', 'Фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'),
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        $date);
return date("Y-m-d", strtotime($date));
}
$connection->set_charset("utf8");
$connection->query( "CREATE TABLE `bills_ru_events` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` datetime  NOT NULL,
  `title` char(230) COLLATE 'utf8_unicode_ci' NOT NULL,
  `url` char(240) COLLATE 'utf8_unicode_ci' NOT NULL UNIQUE)
 ENGINE=MyISAM DEFAULT COLLATE 'utf8_unicode_ci';"); //создать таблицу
$doc = new DOMDocument();
@$doc->loadHTMLFile('http://www.bills.ru/');
$tags_a = $doc->getElementById('bizon_api_news_list')->getElementsByTagName('a');
$tags_span = $doc->getElementById('bizon_api_news_list')->getElementsByTagName('span');
echo "<table>";
for ($c = 0; $c<$tags_a->length; $c++  ) {
        echo "<tr><td>"; echo $url = $tags_a->item($c)->getAttribute('href'); echo "</td><td>"; echo $title = $tags_a->item($c)->nodeValue; echo "</td><td>"; echo $data = newFormatDate(str_replace(')','',str_replace('(от ', '', $tags_span->item($c)->nodeValue))); echo "</td></tr>";
	$connection->query("INSERT INTO `bills_ru_events` (`title`, `url`, `date`) VALUES ('$title', '$url', '$data');");
}
echo "</table aligne>";
?>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<table>
<tr><td id='11'><button id='1' style="text-align: center">1</button></td></tr>
<tr><td id='22'><button id='2' style="text-align: center">2</button></td></tr>
<tr><td id='33'><button id='3' style="text-align: center">3</button></td></tr>
</table>
<script>
    $('#1, #2, #3').click(function(){ var b1 = $('#1').detach(); var b2 = $('#2').detach(); var b3 = $('#3').detach();
  
b1.appendTo('#11');
b2.appendTo('#22');
b3.appendTo('#33');
    });
</script>
