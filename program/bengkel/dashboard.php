<?php
session_start();
require 'config.php';

// Periksa apakah pengguna sudah login dan memiliki peran sebagai 'user'
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user') {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil data pemesanan pengguna berdasarkan user_id
$queryPemesanan = "SELECT * FROM pemesanan WHERE user_id = ?";
$stmtPemesanan = $conn->prepare($queryPemesanan);
$stmtPemesanan->bind_param("i", $user_id);
$stmtPemesanan->execute();
$resultPemesanan = $stmtPemesanan->get_result();

// Ambil riwayat layanan berdasarkan user_id
$queryRiwayat = "SELECT * FROM riwayat_layanan WHERE user_id = ?";
$stmtRiwayat = $conn->prepare($queryRiwayat);
$stmtRiwayat->bind_param("i", $user_id);
$stmtRiwayat->execute();
$resultRiwayat = $stmtRiwayat->get_result();

// Ambil riwayat chat dengan teknisi berdasarkan user_id
$queryChat = "SELECT * FROM chat_teknisi WHERE user_id = ? ORDER BY waktu ASC";
$stmtChat = $conn->prepare($queryChat);
$stmtChat->bind_param("i", $user_id);
$stmtChat->execute();
$resultChat = $stmtChat->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pengguna</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Selamat Datang di Dashboard Anda</h1>
        <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                    <li><a href="logout.php" class="btn-secondary">Logout</a></li>
                </ul>
            </nav>  
       
    </header>

    <div class="dashboard-container">
        <h3>Status Layanan Anda</h3>

        <?php if ($resultPemesanan->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Pemesanan</th>
                        <th>Merk</th>
                        <th>Model</th>
                        <th>Tahun</th>
                        <th>Jadwal Perbaikan</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultPemesanan->fetch_assoc()): ?>
                        <?php $statusLayanan = $row['status']; ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['merk']; ?></td>
                            <td><?= $row['model']; ?></td>
                            <td><?= $row['tahun']; ?></td>
                            <td><?= date('d-m-Y', strtotime($row['jadwal'])); ?></td>
                            <td>
                                <span class="status-label"><?= $statusLayanan; ?></span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada pemesanan layanan yang ditemukan.</p>
        <?php endif; ?>

        <h3>Riwayat Layanan</h3>
        <?php if ($resultRiwayat->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Riwayat</th>
                        <th>Deskripsi</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $resultRiwayat->fetch_assoc()): ?>
                        <?php $riwayatLayanan = $row['deskripsi']; ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $riwayatLayanan; ?></td>
                            <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Tidak ada riwayat layanan yang ditemukan.</p>
        <?php endif; ?>

        <h3>Chat dengan Teknisi</h3>
        <div class="chat-container">
            <?php if ($resultChat->num_rows > 0): ?>
                <?php while ($row = $resultChat->fetch_assoc()): ?>
                    <?php $chatTeknisi = $row['pesan']; ?>
                    <div class="chat-message">
                        <p><strong><?= ($row['teknisi_id'] == $user_id) ? "Anda" : "Teknisi"; ?>:</strong> <?= $chatTeknisi; ?></p>
                        <span class="chat-time"><?= date('H:i - d/m/Y', strtotime($row['waktu'])); ?></span>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Tidak ada pesan yang ditemukan.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
