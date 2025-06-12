<?php
session_start();
require 'config.php'; // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Proses form pemesanan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $merk = trim($_POST['merk']);
    $model = trim($_POST['model']);
    $tahun = trim($_POST['tahun']);
    $masalah = trim($_POST['masalah']);
    $jadwal = trim($_POST['jadwal']);

    // Upload Foto
    $foto_nama = $_FILES['foto_kerusakan']['name'];
    $foto_tmp = $_FILES['foto_kerusakan']['tmp_name'];
    $upload_dir = 'uploads/';
    $foto_path = $upload_dir . basename($foto_nama);

    if (!empty($merk) && !empty($model) && !empty($tahun) && !empty($masalah) && !empty($jadwal) && move_uploaded_file($foto_tmp, $foto_path)) {
        // Simpan data ke database
        $stmt = $conn->prepare('INSERT INTO pemesanan (user_id, merk, model, tahun, masalah, foto_kerusakan, jadwal) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('issssss', $user_id, $merk, $model, $tahun, $masalah, $foto_path, $jadwal);

        if ($stmt->execute()) {
            $success = 'Permintaan berhasil dikirim! Kami akan segera menghubungi Anda.';
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }

        $stmt->close();
    } else {
        $error = 'Silakan isi semua data dan unggah foto kerusakan.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pemesanan - Perbaikan Mobil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Form Pemesanan Layanan</h1>
            <nav>
                <a href="index.php">Beranda</a>
                <a href="layanan-detail.php">Layanan</a>
                <a href="kontak.php">Kontak</a>
            </nav>
        </div>
    </header>

    <div class="form-container">
        <h2>Pesan Layanan Perbaikan Mobil</h2>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="form-pemesanan.php" method="POST" enctype="multipart/form-data">
            <label for="merk">Merk Mobil:</label>
            <input type="text" name="merk" id="merk" required placeholder="Masukkan merk mobil">

            <label for="model">Model Mobil:</label>
            <input type="text" name="model" id="model" required placeholder="Masukkan model mobil">

            <label for="tahun">Tahun Produksi:</label>
            <input type="number" name="tahun" id="tahun" required placeholder="Masukkan tahun produksi">

            <label for="masalah">Deskripsi Masalah:</label>
            <textarea name="masalah" id="masalah" required placeholder="Jelaskan masalah yang dialami"></textarea>

            <label for="foto_kerusakan">Upload Foto Kerusakan:</label>
            <input type="file" name="foto_kerusakan" id="foto_kerusakan" required>

            <label for="jadwal">Pilih Jadwal Perbaikan:</label>
            <input type="date" name="jadwal" id="jadwal" required>

            <button type="submit" class="btn-primary">Kirim Permintaan</button>
        </form>

        <a href="https://wa.me/62895379229956?text=Saya%20ingin%20memesan%20layanan%20perbaikan%20mobil%20untuk%20<?php echo urlencode($merk . ' ' . $model); ?>" class="btn-secondary">Lanjutkan ke WhatsApp</a>
    </div>
</body>
</html>
