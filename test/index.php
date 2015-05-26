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
//[a-zA-Z\d]+.+[ixt]
closedir($handle); 
}
?>
<?php
$homepage = file_get_contents('http://www.bills.ru/');
//print $homepage;

$doc = new DOMDocument();
$doc->preserveWhiteSpace = FALSE;
$doc->loadHTMLFile('http://www.bills.ru/');

$tags = $doc->getElementsByTagName('a');

foreach ($tags as $tag) {
       echo $tag->getAttribute('href').' | '.$tag->nodeValue."\n";
}
echo $doc->saveHTML();

   // 
   // @$doc->loadHTMLFile('http://www.bills.ru/');
    //echo $doc->saveHTML();


?>