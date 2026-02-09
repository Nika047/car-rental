<?php
$server = "localhost";
$user = "root";
$password = "";
$DB = "sem8";

$link = mysqli_connect($server, $user, $password, $DB);

if (!$link) {
    die("Ошибка подключения к БД: " . mysqli_connect_error());
}

mysqli_set_charset($link, "utf8");
?>
