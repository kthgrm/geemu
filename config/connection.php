<?php

$host = $_ENV['DB_HOST'] ?? 'localhost';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';
$dbname = $_ENV['DB_NAME'] ?? 'geemu';

$conn = mysqli_connect($host, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
