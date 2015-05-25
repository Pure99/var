<?php  /*
class MyDestructableClass {
     function __construct() {
         print "Конструктор\n";
         $this->name = "MyDestructableClass";
     }

     function __destruct() {
         print "Уничтожается " . $this->name . "\n";
     }
}

$obj = new MyDestructableClass();
$obj->__destruct();   */
?>

<?php
	final class init {
		public function create() {
	 $connection = new mysqli('localhost', 'root', '', 'test'); // устанавливаем соединение с БД
	 $connection->query( "CREATE TABLE `test` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `script_name` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `start_time` int NOT NULL,
  `end_time` int NOT NULL,
  `result` text COLLATE 'utf8_unicode_ci' NOT NULL
) COLLATE 'utf8_unicode_ci';"); //создать таблицу
		}
		public function fill() {
			$connection = new mysqli('localhost', 'root', '', 'test'); // устанавливаем соединение с БД
			for ($k=0; $k<10; $k++){
			$connection->query("INSERT INTO `test` (`script_name`, `start_time`, `end_time`, `result`) VALUES ('', CURTIME(), '', 'normal');");                      // записать единицу в KOEF 
		}}
		 
		
		
		
		function __construct() {
			$this->create();
			$this->fill();
		}
	}
	$test = new init;
	
?>

