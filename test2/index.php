<?php session_start();
require ('config.php');
$connection->query( "CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `gender` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `familia` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `Imya` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `Othestvo` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `birthday` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `avatar` blob NOT NULL,
  `color` text(25) COLLATE 'utf8_unicode_ci' NOT NULL,
  `lihnye_kachestva` text COLLATE 'utf8_unicode_ci' NOT NULL,
  `navyki` text(25) COLLATE 'utf8_unicode_ci' NOT NULL
) COLLATE 'utf8_unicode_ci';"); //создать таблицу
?>
<h1>Анкетирование</h1>






