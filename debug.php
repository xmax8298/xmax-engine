<?php
include 'koneksi.php';

$user = 'boss_xmax';
$pass = 'admin123';

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    $hash_di_db = $row['password'];
    echo "Hash di DB: " . $hash_di_db . "<br>";
    
    if (password_verify($pass, $hash_di_db)) {
        echo "<h2 style='color:green'>BERHASIL! password_verify bekerja.</h2>";
    } else {
        echo "<h2 style='color:red'>GAGAL! Password asli dan Hash tidak cocok.</h2>";
        echo "Tips: Pastikan tidak ada spasi di awal/akhir password saat lu insert.";
    }
} else {
    echo "<h2 style='color:orange'>User tidak ditemukan di database!</h2>";
}
?>