<?php
$servername = "db";          // gunakan nama service MySQL di docker-compose
$username = "root";
$password = "root";          // samakan dengan MYSQL_ROOT_PASSWORD
$database = "db_perbaikan_mobil";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>
