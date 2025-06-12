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

    // Hapus pemesanan dari database
    $stmt = $conn->prepare('DELETE FROM pemesanan WHERE id = ?');
    $stmt->bind_param('i', $id_pesanan);

    if ($stmt->execute()) {
        header('Location: dashboard-admin.php');
        exit();
    } else {
        echo 'Gagal menghapus pemesanan.';
    }

    $stmt->close();
}
?>
