<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_pesanan = $_POST['id_pesanan'];
    $estimated_cost = $_POST['estimated_cost'];

    // Validate and sanitize input
    $id_pesanan = filter_var($id_pesanan, FILTER_VALIDATE_INT);
    $estimated_cost = filter_var($estimated_cost, FILTER_VALIDATE_FLOAT);

    if ($id_pesanan !== false && $estimated_cost !== false) {
        $stmt = $conn->prepare('UPDATE pemesanan SET estimated_cost = ? WHERE id = ?');
        $stmt->bind_param('di', $estimated_cost, $id_pesanan); // 'd' for double (float), 'i' for integer

        if ($stmt->execute()) {
            // Success
        } else {
            // Error
        }
        $stmt->close();
    }
}

header('Location: dashboard-admin.php');
exit();
?>