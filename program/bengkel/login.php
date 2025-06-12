<?php
session_start();
require 'config.php'; // Pastikan Anda memiliki file config.php untuk koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Cek apakah email dan password tidak kosong
    if (!empty($email) && !empty($password)) {
        // Query untuk mendapatkan data pengguna berdasarkan email
        $query = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah pengguna ditemukan
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                // Jika berhasil, simpan data pengguna ke sesi
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_nama'] = $row['nama'];
                $_SESSION['user_role'] = $row['role'];
                
                // Arahkan berdasarkan peran
                if ($row['role'] === 'admin') {
                    header("Location: dashboard-admin.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit();
            } else {
                $error = "Password salah. Silakan coba lagi.";
            }
        } else {
            $error = "Email tidak terdaftar.";
        }
    } else {
        $error = "Harap isi semua kolom.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Perbaikan Mobil</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="container">
            
            <nav>
                <ul>
                    <li><a href="index.php">Beranda</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Masukkan email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan password">

            <button type="submit">Login</button>
        </form>
        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
    </div>
</body>
</html>
