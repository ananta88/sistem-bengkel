<?php
session_start();
require 'config.php'; // Pastikan Anda memiliki file config.php untuk koneksi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    // Cek apakah email dan password tidak kosong
    if (!empty($email) && !empty($password)) {
        // Query untuk mendapatkan data admin berdasarkan email
        $query = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Cek apakah admin ditemukan
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            // Verifikasi password
            if (password_verify($password, $row['password'])) {
                // Jika berhasil, simpan data admin ke sesi
                $_SESSION['admin_id'] = $row['id'];
                $_SESSION['admin_nama'] = $row['nama'];
                // Arahkan ke dashboard admin
                header("Location: dashboard-admin.php");
                exit();
            } else {
                $error = "Password salah. Silakan coba lagi.";
            }
        } else {
            $error = "Email tidak terdaftar atau bukan admin.";
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
    <title>Login Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="login-container">
        <h1>Login Admin</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="login_admin.php" method="POST">
            <label for="email">Email:</label>
            <input type="email" name="email" id="email" required placeholder="Masukkan email">

            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required placeholder="Masukkan password">

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>