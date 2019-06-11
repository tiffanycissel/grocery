<?php

$name = '';
$key = '';
$host = '';
$db = '';
$charset = 'utf8';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET  NAMES \'UTF8\''
);

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $conn = new PDO($dsn,$name,$key,$options);
} catch (PDOException $e) {
    echo 'Connection failed: '.$e->getMessage();
}
