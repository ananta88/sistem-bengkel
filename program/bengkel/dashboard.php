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
$stmt = $conn->prepare("SELECT * FROM pemesanan WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

// Ambil riwayat layanan berdasarkan user_id
$queryRiwayat = "
    SELECT rl.*, p.merk, p.model, p.tahun
    FROM riwayat_layanan rl
    JOIN pemesanan p ON rl.pemesanan_id = p.id
    WHERE rl.user_id = ?
    ORDER BY rl.tanggal DESC
";
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

        <?php if ($result->num_rows > 0): ?>
        <table border="1" cellpadding="8" cellspacing="0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Merk</th>
                    <th>Model</th>
                    <th>Tahun</th>
                    <th>Masalah</th>
                    <th>Foto</th>
                    <th>Jadwal</th>
                    <th>Perkiraan Biaya</th>
                    <th>Status</th>
                    <th>Chat WA</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['merk']) ?></td>
                    <td><?= htmlspecialchars($row['model']) ?></td>
                    <td><?= $row['tahun'] ?></td>
                    <td><?= htmlspecialchars($row['masalah']) ?></td>
                    <td>
                        <?php if (!empty($row['foto_kerusakan'])): ?>
                        <img src="<?= $row['foto_kerusakan'] ?>" width="100">
                        <?php else: ?>
                        Tidak ada foto
                        <?php endif; ?>
                    </td>
                    <td><?= date('d-m-Y', strtotime($row['jadwal'])) ?></td>
                    <td>
                        <?= $row['estimated_cost'] !== null 
                            ? 'Rp ' . number_format($row['estimated_cost'], 0, ',', '.') 
                            : 'Belum tersedia' ?>
                    </td>
                    <td>
                        <?php if ($row['status'] === 'Selesai'): ?>
                        <span class="status-selesai"><?= htmlspecialchars($row['status']) ?></span>
                        <?php else: ?>
                        <span class="status-default"><?= htmlspecialchars($row['status']) ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php
                            $nomor_teknisi = "6281234567890"; // ganti sesuai nomor WA teknisi/admin
                            $pesan_wa = urlencode("Halo Admin, saya ingin mengonfirmasi layanan untuk mobil {$row['merk']} {$row['model']} ({$row['tahun']}) dengan jadwal pada tanggal " . date('d-m-Y', strtotime($row['jadwal'])) . ". Masalah yang saya laporkan: {$row['masalah']}");
                            $link_wa = "https://wa.me/{$nomor_teknisi}?text={$pesan_wa}";
                        ?>
                        <a href="<?= $link_wa ?>" target="_blank" class="btn-wa">Chat WA</a>
                    </td>

                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Tidak ada data pemesanan ditemukan.</p>
        <?php endif; ?>

        <h3>Riwayat Layanan</h3>
        <?php if ($resultRiwayat->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kendaraan</th>
                    <th>Deskripsi</th>
                    <th>Tanggal</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            $no = 1;
            while ($row = $resultRiwayat->fetch_assoc()): 
            ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $row['merk'] . ' ' . $row['model'] . ' (' . $row['tahun'] . ')'; ?></td>
                    <td><?= $row['deskripsi']; ?></td>
                    <td><?= date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Tidak ada riwayat layanan yang ditemukan.</p>
        <?php endif; ?>

        <!-- <h3>Chat dengan Teknisi</h3>
        <div class="chat-container">
            <?php if ($resultChat->num_rows > 0): ?>
            <?php while ($row = $resultChat->fetch_assoc()): ?>
            <?php $chatTeknisi = $row['pesan']; ?>
            <div class="chat-message">
                <p><strong><?= ($row['teknisi_id'] == $user_id) ? "Anda" : "Teknisi"; ?>:</strong> <?= $chatTeknisi; ?>
                </p>
                <span class="chat-time"><?= date('H:i - d/m/Y', strtotime($row['waktu'])); ?></span>
            </div>
            <?php endwhile; ?>
            <?php else: ?>
            <p>Tidak ada pesan yang ditemukan.</p>
            <?php endif; ?>
        </div> -->
    </div>
</body>

</html>