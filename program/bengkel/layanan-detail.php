<?php
session_start();
require 'config.php'; // Koneksi database

// Daftar layanan dan deskripsi
$services = [
    "Servis Berkala" => "Servis rutin untuk menjaga performa mobil Anda tetap optimal. Termasuk pengecekan oli, filter, dan komponen penting lainnya.",
    "Perbaikan Mesin" => "Perbaikan untuk masalah mesin seperti overheating, suara mesin kasar, dan kerusakan lainnya.",
    "Pengecatan" => "Layanan pengecatan mobil dengan warna pilihan Anda. Gunakan cat berkualitas tinggi untuk hasil yang tahan lama.",
    "Perbaikan Body" => "Perbaikan kerusakan pada bodi mobil seperti penyok, retak, atau goresan."
];

// Estimasi biaya per layanan (per unit kerja)
$cost_estimates = [
    "Servis Berkala" => 500000,
    "Perbaikan Mesin" => 1500000,
    "Pengecatan" => 2000000,
    "Perbaikan Body" => 1000000
];

// Hitung estimasi biaya
$estimated_cost = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service_type'], $_POST['quantity'])) {
    $service_type = $_POST['service_type'];
    $quantity = intval($_POST['quantity']);
    if (array_key_exists($service_type, $cost_estimates)) {
        $estimated_cost = $cost_estimates[$service_type] * $quantity;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Detail - Perbaikan Mobil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Detail Layanan</h1>
            <nav>
                <a href="index.php">Beranda</a>
                <a href="layanan-detail.php">Layanan</a>
                <a href="kontak.php">Kontak</a>
            </nav>
        </div>
    </header>

    <div class="service-container">
        <h2>Layanan Kami</h2>
        <?php foreach ($services as $service => $description): ?>
            <div class="service-item">
                <h3><?php echo $service; ?></h3>
                <p><?php echo $description; ?></p>
            </div>
        <?php endforeach; ?>

        <h2>Estimasi Biaya</h2>
        <form action="layanan-detail.php" method="POST" class="estimate-form">
            <label for="service_type">Pilih Layanan:</label>
            <select name="service_type" id="service_type" required>
                <?php foreach ($cost_estimates as $service => $cost): ?>
                    <option value="<?php echo $service; ?>"><?php echo $service; ?></option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Jumlah (misal: jam kerja atau unit):</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1" required>

            <button type="submit">Hitung Estimasi</button>
        </form>

        <?php if ($estimated_cost > 0): ?>
            <div class="estimate-result">
                <p>Estimasi biaya untuk layanan <strong><?php echo $service_type; ?></strong> adalah: <strong>Rp <?php echo number_format($estimated_cost, 0, ',', '.'); ?></strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
