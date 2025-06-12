<?php
session_start();
require 'config.php'; // Koneksi ke database

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Proses form feedback
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $id_pesanan = $_POST['id_pesanan'];
    $feedback = trim($_POST['feedback']);
    $rating = (int) $_POST['rating'];

    // Validasi input
    if (!empty($feedback) && $rating > 0 && $rating <= 5) {
        // Simpan feedback dan rating ke database
        $stmt = $conn->prepare('UPDATE pemesanan SET feedback = ?, rating = ? WHERE id = ? AND user_id = ?');
        $stmt->bind_param('siii', $feedback, $rating, $id_pesanan, $user_id);

        if ($stmt->execute()) {
            $success = 'Terima kasih atas ulasan Anda!';
        } else {
            $error = 'Terjadi kesalahan. Silakan coba lagi.';
        }

        $stmt->close();
    } else {
        $error = 'Silakan berikan ulasan dan penilaian yang valid (1-5).';
    }
}

// Ambil data pemesanan yang bisa diberi ulasan
$stmt = $conn->prepare('SELECT id, merk, model FROM pemesanan WHERE user_id = ? AND status = "Selesai" AND feedback IS NULL');
$stmt->bind_param('i', $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$pemesanan = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Berikan Ulasan - Perbaikan Mobil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <h1>Berikan Ulasan Layanan</h1>
            <nav>
                <a href="index.php">Beranda</a>
                <a href="dashboard.php">Dashboard</a>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="form-container">
        <h2>Beri Ulasan</h2>
        <?php if (isset($success)): ?>
            <p class="success"><?php echo $success; ?></p>
        <?php elseif (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>

        <?php if (!empty($pemesanan)): ?>
            <form action="submit_feedback.php" method="POST">
                <label for="id_pesanan">Pilih Pesanan:</label>
                <select name="id_pesanan" id="id_pesanan" required>
                    <?php foreach ($pemesanan as $pesanan): ?>
                        <option value="<?php echo $pesanan['id']; ?>">
                            <?php echo htmlspecialchars($pesanan['merk'] . ' ' . $pesanan['model']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="rating">Penilaian (1-5):</label>
                <input type="number" name="rating" id="rating" min="1" max="5" required>

                <label for="feedback">Ulasan:</label>
                <textarea name="feedback" id="feedback" required placeholder="Berikan ulasan mengenai layanan kami"></textarea>

                <button type="submit" class="btn-primary">Kirim Ulasan</button>
            </form>
        <?php else: ?>
            <p>Tidak ada layanan yang selesai untuk diberi ulasan.</p>
        <?php endif; ?>
    </div>
</body>
</html>
