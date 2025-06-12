<?php
require 'config.php';

$sql = "SELECT * FROM users";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    echo "Koneksi database berhasil. Jumlah user: " . $result->num_rows;
} else {
    echo "Terkoneksi, tapi data user kosong atau query gagal.";
}
?>
