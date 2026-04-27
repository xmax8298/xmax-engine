<?php
include 'koneksi.php';

$user = 'boss_xmax';
$pass_baru = 'admin123';
$hash = password_hash($pass_baru, PASSWORD_BCRYPT);

$stmt = $conn->prepare("UPDATE users SET password = ? WHERE username = ?");
$stmt->bind_param("ss", $hash, $user);

if ($stmt->execute()) {
    echo "<h2>Password boss_xmax berhasil di-reset jadi: admin123</h2>";
    echo "Hash baru: " . $hash;
} else {
    echo "Gagal update: " . $conn->error;
}
?>