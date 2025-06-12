<?php
session_start();
require 'config.php'; // File konfigurasi database

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $status = $_POST['status'];

    // Ambil data pesanan sebelum update
    $stmtSelect = $conn->prepare("SELECT * FROM pemesanan WHERE id = ?");
    $stmtSelect->bind_param("i", $id_pesanan);
    $stmtSelect->execute();
    $result = $stmtSelect->get_result();
    $pesanan = $result->fetch_assoc();
    $stmtSelect->close();

    if (!$pesanan) {
        echo "Pesanan tidak ditemukan.";
        exit();
    }

    // Update status pesanan
    $stmtUpdate = $conn->prepare('UPDATE pemesanan SET status = ? WHERE id = ?');
    $stmtUpdate->bind_param('si', $status, $id_pesanan);

    if ($stmtUpdate->execute()) {
        // Jika status diset menjadi "Selesai", masukkan ke riwayat
        if ($status === 'Selesai') {
            $user_id = $pesanan['user_id'];
            $deskripsi = "Perbaikan selesai untuk " . $pesanan['merk'] . " " . $pesanan['model'] . " tahun " . $pesanan['tahun'];
            $tanggal = date('Y-m-d');

            // Cek duplikat riwayat
            $stmtCek = $conn->prepare("SELECT id FROM riwayat_layanan WHERE user_id = ? AND pemesanan_id = ?");
            $stmtCek->bind_param("ii", $user_id, $id_pesanan);
            $stmtCek->execute();
            $cekResult = $stmtCek->get_result();

            if ($cekResult->num_rows === 0) {
                $stmtInsert = $conn->prepare("INSERT INTO riwayat_layanan (pemesanan_id, user_id, deskripsi, tanggal) VALUES (?, ?, ?, ?)");
                $stmtInsert->bind_param("iiss", $id_pesanan, $user_id, $deskripsi, $tanggal);
                $stmtInsert->execute();
                $stmtInsert->close();
            }

            $stmtCek->close();
        }

        $stmtUpdate->close();
        header('Location: dashboard-admin.php');
        exit();
    } else {
        echo 'Gagal memperbarui status.';
    }

    $stmtUpdate->close();
}
?>
