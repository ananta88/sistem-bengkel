<?php
session_start();
require 'config.php'; // File ini berisi koneksi ke database

// Cek apakah pengguna sudah login
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Proses registrasi saat formulir dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);

    if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {
        // Validasi password
        if ($password !== $confirm_password) {
            $error = 'Password dan konfirmasi password tidak sama.';
        } else {
            // Cek apakah email sudah digunakan
            $stmt = $conn->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = 'Email sudah terdaftar. Silakan gunakan email lain.';
            } else {
                // Hash password dan simpan ke database
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
                $stmt->bind_param('sss', $username, $email, $hashed_password);

                if ($stmt->execute()) {
                    // Redirect ke halaman login setelah berhasil mendaftar
                    header('Location: login.php');
                    exit();
                } else {
                    $error = 'Terjadi kesalahan. Silakan coba lagi.';
                }
            }

            $stmt->close();
        }
    } else {
        $error = 'Silakan isi semua data yang diminta.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Perbaikan Mobil</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="register-container">
        <h1>Daftar Akun</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <label for="username">Username:</label>
            <input type="text" name="username" id="username" required placeholder="Masukkan username">

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Masukkan email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan password">

            <label for="confirm_password">Konfirmasi Password:</label>
            <input type="password" name="confirm_password" id="confirm_password" required placeholder="Konfirmasi password">

            <button type="submit">Daftar</button>
        </form>
        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</body>
</html>
