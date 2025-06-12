<?php
session_start();
require 'config.php'; // File konfigurasi database

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];

    // Update status pemesanan di database
    $stmt = $conn->prepare('UPDATE pemesanan SET status = ? WHERE id = ?');
    $stmt->bind_param('si', $status, $id_pesanan);

    if ($stmt->execute()) {
        header('Location: dashboard-admin.php');
        exit();
    } else {
        echo 'Gagal memperbarui status.';
    }

    $stmt->close();
}
?>
