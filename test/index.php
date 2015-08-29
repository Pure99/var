<?php
/**
* Создаем подключение к базе данных
*/
$connection = new mysqli('localhost', 'root', '', 'test'); 
if (mysqli_connect_errno()) {
    printf("Ошибка подключения к базе данных: %s\n", mysqli_connect_error());
    exit();
}
/**
* Указываем кодировку подключения
*/
//$connection->set_charset("utf8");
/**
* Задача №1
*
* Класс init
*/
	final class init {
		/**
		* Метод create()
		* Доступен только для методов класса
		* Создает таблицу 'test'
		*/
	private function create() {
	global $connection;
	$connection->query( "CREATE TABLE `test` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `script_name` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `result` text COLLATE 'utf8_unicode_ci' NOT NULL
) COLLATE 'utf8_unicode_ci';"); 
	}
		/**
		* Метод fill()
		* Доступен только для методов класса
		* Вставляет в тыблицу 10 строк с случайными значениями
		*/
	private function fill() {
		global $connection;
        $result = array ('normal', 'illegal', 'failed', 'success');
	for ($k=1; $k<11; $k++){
        $x=mt_rand(0, 3);
	$connection->query("INSERT INTO `test` (`script_name`, `start_time`, `end_time`, `result`) VALUES (ROUND((RAND() * (1000 - 0)) + 0), ROUND((RAND() * (1000 - 0)) + 0), ROUND((RAND() * (1000 - 0)) + 0), '$result[$x]');"); 
		}
	}
		/**
		* Конструктор 
		* Содержит методы create() и fill()
		*/
		function __construct() {
			$this->create();
			$this->fill();
		}
		/**
		* Метод get()
		* Доступен извне класса
		* Выбирает из таблицы test, данные по критерию: result среди значений 'normal' и 'success'
		*/
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
/**
* Задача №2
* Создаем начальные условия
*/
$connection->query( "CREATE TABLE `info` ( `id` int(11) NOT NULL auto_increment, `name` varchar(255) default NULL, `desc` text default NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=cp1251;"); 
$connection->query( "CREATE TABLE `data` (`id` int(11) NOT NULL auto_increment, `date` date default NULL, `value` INT(11) default NULL, PRIMARY KEY (`id`)) ENGINE=MyISAM DEFAULT CHARSET=cp1251;"); 
$connection->query( "CREATE TABLE `link` (`data_id` int(11) NOT NULL,`info_id` int(11) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=cp1251;");
$connection->query( "select * from data,link,info where link.info_id = info.id and link.data_id = data.id;");
/** копируем столбец data_id из link в info*/
$connection->query( "ALTER TABLE `info` ADD `data_id` INT NOT NULL;");
$connection->query( "INSERT INTO `test`.`info` (`data_id`) SELECT `data_id` FROM `test`.`link`;");
/** удаляем таблицу link */
$connection->query( "DROP TABLE `link`");
/** создаем новый запрос*/
$connection->query( "select * from data,info where info.data_id = data.id;");


?>
<?php
/**
* Задача №3
*
* Открываем каталог datafiles
*/
if ($handle = opendir('./datafiles/')) {
    echo "Дескриптор каталога: $handle<br>";
    echo "Файлы:<br>";
/**
* Читаем каталог datafiles и выводим имена файлов, состоящие из цифр и букв латинского алфавита, с расширением ixt
*/
    while (false !== ($file = readdir($handle))) { 
	if ($file != "." && $file != ".." &&  preg_match("/\.(:?ixt)$/i", $file, $matches) && ! preg_match("/[^a-z0-9\.]+/", $file, $matches)) {
        echo "$file<br>";
	}
    }
closedir($handle); 
}
?>
<?php
/**
* Задача №4
*/

/**
* Функция преобразования строки с датой на русском в дату формата "Y-m-d"
*/
function newFormatDate($date) {
    $date = str_replace(array('янв', 'Фев', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сен', 'окт', 'ноя', 'дек'),
                        array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'),
                        $date);
return date("Y-m-d", strtotime($date));
}

					

/**
* Создаем табдицу 'bills_ru_events'
*/
$connection->query( "CREATE TABLE `bills_ru_events` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `date` datetime  NOT NULL,
  `title` char(230) COLLATE 'utf8_unicode_ci' NOT NULL,
  `url` char(240) COLLATE 'utf8_unicode_ci' NOT NULL UNIQUE)
 ENGINE=MyISAM DEFAULT COLLATE 'utf8_unicode_ci';"); 
/**
* Создаем новый объект и загружаем содержимое сайта
*/
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
setlocale(LC_ALL, 'ru_RU.UTF-8');
						
											
							echo strftime('%b %B %h %m', strtotime("июнь"));
?>

<!-- Задача №5      -->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<table>
<tr><td id='11'><button id='1' style="text-align: center">1</button></td></tr>
<tr><td id='22'><button id='2' style="text-align: center">2</button></td></tr>
<tr><td id='33'><button id='3' style="text-align: center">3</button></td></tr>
</table>
<script>
    $('#1, #2, #3').click(function(){ 
	var m = [$('#11').children().detach(), $('#22').children().detach(), $('#33').children().detach()]; // создаем массив с кнопками по порядку
	m = m.slice(1).concat(m.slice(0,1));  //сдвигаем массив
	m[0].appendTo('#11'); // всатвляем кнопку
	m[1].appendTo('#22');
	m[2].appendTo('#33');
    });
</script>
