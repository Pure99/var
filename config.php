<?php
$db_host = 'localhost'; //��� MySQL-�������
$db_user = 'root'; // ��� ������������
$db_pass = ''; // ������
$db_name = 'base'; // ��� ����
// ������������� ���������� � ��

$connection = new mysqli($db_host, $db_user, $db_pass, $db_name);
$connection->set_charset("utf8");
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
?>