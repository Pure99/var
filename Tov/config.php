<?php
$db_host = 'localhost'; //��� MySQL-�������
$db_user = 'root'; // ��� ������������
$db_pass = ''; // ������
$db_name = 'base'; // ��� ����
// ������������� ���������� � ��
mysql_connect($db_host,$db_user,$db_pass) or die('����������� ����������� � MySQL-�������.<br />'.mysql_error());
mysql_select_db($db_name) or die('Ne podkluchaetsa k .'.$db_name .'<br />'.mysql_error().'<br><a href="install.php">install</a>');
mysql_query("set character_set_client='utf8'");
mysql_query("set character_set_results='utf8'");
mysql_query("set collation_connection='utf8_unicode_ci'");

function zapros($select) {
 $result = mysql_query($select);
 $row = mysql_fetch_array($result);
 return($row[0]);
}
$dni_arr=array("","�����������","�������","�����","�������","�������","�������")
?>