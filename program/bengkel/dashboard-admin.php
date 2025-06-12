<?php
session_start();
require 'config.php';

// Cek apakah pengguna adalah admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Ambil semua data pemesanan
$stmt = $conn->prepare('
    SELECT p.id, p.user_id, p.merk, p.model, p.tahun, p.masalah, p.status, p.jadwal, 
           p.feedback, p.rating, p.estimated_cost, p.foto_kerusakan, 
           u.username AS user_name 
    FROM pemesanan p 
    LEFT JOIN users u ON p.user_id = u.id 
    ORDER BY p.created_at DESC
');
$stmt->execute();
$result = $stmt->get_result();
$pemesanan = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Perbaikan Mobil</title>
    <link rel="stylesheet" href="css/styles_dsh.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <header>
        <div class="header-container">
            <h1>Dashboard Admin</h1>
            <nav>
                <a href="logout.php">Logout</a>
            </nav>
        </div>
    </header>

    <div class="dashboard-container">
        <h2>Kelola Pemesanan Layanan</h2>

        <?php if (!empty($pemesanan)): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Pengguna</th>
                    <th>Mobil</th>
                    <th>Masalah</th>
                    <th>Jadwal</th>
                    <th>Status</th>
                    <th>Perkiraan Biaya</th>
                    <th>Foto</th>
                    <th>Feedback</th>
                    <th>Rating</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($pemesanan as $pesanan): ?>
                <tr>
                    <td><?php echo $pesanan['id']; ?></td>
                    <td><?php echo htmlspecialchars($pesanan['user_name']); ?></td>
                    <td><?php echo htmlspecialchars($pesanan['merk'] . ' ' . $pesanan['model'] . ' (' . $pesanan['tahun'] . ')'); ?>
                    </td>
                    <td><?php echo htmlspecialchars($pesanan['masalah']); ?></td>
                    <td><?php echo htmlspecialchars($pesanan['jadwal']); ?></td>

                    <!-- Status -->
                    <td>
                        <form action="update_status.php" method="POST">
                            <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id']; ?>">
                            <select name="status" onchange="this.form.submit()">
                                <option value="Permintaan Diterima"
                                    <?php echo ($pesanan['status'] == 'Permintaan Diterima') ? 'selected' : ''; ?>>
                                    Permintaan Diterima</option>
                                <option value="Sedang Diproses"
                                    <?php echo ($pesanan['status'] == 'Sedang Diproses') ? 'selected' : ''; ?>>Sedang
                                    Diproses</option>
                                <option value="Selesai"
                                    <?php echo ($pesanan['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            </select>
                        </form>
                    </td>

                    <!-- Perkiraan Biaya -->
                    <td>
                        <form action="update_estimated_cost.php" method="POST">
                            <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id']; ?>">
                            <input type="number" step="0.01" name="estimated_cost"
                                value="<?php echo htmlspecialchars($pesanan['estimated_cost']); ?>"
                                onchange="this.form.submit()">
                        </form>
                    </td>

                    <!-- Foto Kerusakan -->
                    <td>
                        <?php if (!empty($pesanan['foto_kerusakan'])): ?>
                        <a href="#"
                            onclick="showImageModal('<?= htmlspecialchars($pesanan['foto_kerusakan']) ?>'); return false;">
                            <img src="<?= htmlspecialchars($pesanan['foto_kerusakan']) ?>" alt="Foto Kerusakan"
                                width="100" style="border-radius:4px;">
                        </a>
                        <?php else: ?>
                        <em>Tidak ada</em>
                        <?php endif; ?>
                    </td>

                    <!-- Feedback & Rating -->
                    <td><?php echo !empty($pesanan['feedback']) ? htmlspecialchars($pesanan['feedback']) : 'Belum ada'; ?>
                    </td>
                    <td><?php echo !empty($pesanan['rating']) ? htmlspecialchars($pesanan['rating']) : '-'; ?></td>

                    <!-- Aksi -->
                    <td>
                        <form action="delete_pemesanan.php" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus?');">
                            <input type="hidden" name="id_pesanan" value="<?php echo $pesanan['id']; ?>">
                            <button type="submit" class="btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>Tidak ada pemesanan layanan saat ini.</p>
        <?php endif; ?>
    </div>

    <!-- Modal untuk preview gambar -->
    <!-- Modal untuk Gambar -->
    <div id="imageModal" class="modal"
        style="display:none; position:fixed; z-index:9999; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.7); align-items:center; justify-content:center;">
        <span onclick="closeImageModal()"
            style="position:absolute; top:20px; right:30px; color:white; font-size:30px; cursor:pointer;">&times;</span>
        <img id="modalImage" src="" alt="Foto Kerusakan"
            style="max-width:90%; max-height:90%; border:4px solid white; border-radius:8px;">
    </div>



    <script>
    function showImageModal(imageSrc) {
        const modal = document.getElementById('imageModal');
        const modalImg = document.getElementById('modalImage');
        modalImg.src = imageSrc;
        modal.style.display = 'flex';
    }

    function closeImageModal() {
        document.getElementById('imageModal').style.display = 'none';
    }
    </script>


</body>

</html>