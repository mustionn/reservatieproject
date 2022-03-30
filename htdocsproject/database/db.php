<?php
$host = 'localhost';
$port = '3306';
$username = 'camping-la-rustique';
$password = 'camping-la-rustique';
$database = 'camping-la-rustique';

$dsn = "mysql:dbname=$database;host=$host;port=$port";
$db = new PDO($dsn, $username, $password);