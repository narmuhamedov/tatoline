<?php
$server = '127.0.0.1:3306';
$name = 'root';
$password = 'root';
$database = 'TatooLine';

$conn = mysqli_connect($server, $name, $password, $database);

if (!$conn) {
    die("Ошибка подключения к базе данных");
}

mysqli_set_charset($conn, "utf8mb4");
//cscscscs
?>