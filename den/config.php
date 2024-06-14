<?php
$servername = "localhost";
$username = "root";
$password = "190315";
$dbname = "odev";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Veritabanı bağlantısı başarısız: " . $conn->connect_error);
}
?>
