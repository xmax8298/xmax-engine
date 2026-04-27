<?php
include 'koneksi.php';

// Data User Baru (Silakan ganti di sini sesuai kebutuhan)
$user_baru = "boss_xmax_admin";
$pass_baru = "admin12345"; // Password asli yang mau lu pake
$role_baru = "superadmin"; // Bisa diganti 'admin' atau 'user'

// PROSES ENKRIPSI (Cybersecurity Standard)
$hashed_password = password_hash($pass_baru, PASSWORD_BCRYPT);

// Masukkan ke Database pakai Prepared Statement
$stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $user_baru, $hashed_password, $role_baru);

if ($stmt->execute()) {
    echo "<h3>User Baru Berhasil Dibuat!</h3>";
    echo "Username: " . $user_baru . "<br>";
    echo "Role: " . $role_baru . "<br>";
    echo "Password Hash (yang tersimpan di DB): " . $hashed_password;
} else {
    echo "Gagal membuat user: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
