<?php
session_start();
require 'config.php';

// Data admin baru
$username = 'admin';
$email = 'admin@example.com';
$password = 'admin123'; // password asli
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$role = 'admin';

// Cek apakah admin sudah ada
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND role = ?");
$stmt->bind_param("ss", $email, $role);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "Akun admin sudah ada.";
} else {
    $stmt->close();
    $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "Akun admin berhasil dibuat.<br>";
        echo "Email: <b>$email</b><br>Password: <b>$password</b>";
    } else {
        echo "Gagal membuat akun admin: " . $stmt->error;
    }
}
$stmt->close();
$conn->close();
?>
