<?php

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "cadastro";
$port = "3306";

$conn = new PDO("mysql:host=$host;post=$port;dbname=" . $dbname, $user, $pass);
