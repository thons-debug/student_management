<?php
// config/db.php

$host = "127.0.0.1";
$user = "root";
$pass = "";        // XAMPP default blank hota hai
$dbname = "student_management";
$port = 3306;      // IMPORTANT (image me dikha)

$conn = mysqli_connect($host, $user, $pass, $dbname, $port);

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");
?>