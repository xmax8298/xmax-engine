<?php
// 1. Session cuma boleh SATU kali di paling atas
session_start();

// 2. Hubungkan koneksi
include 'koneksi.php';

// 3. NYALAKAN error biar kita tahu kenapa dia loading terus
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Ambil data & bersihkan spasi
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    // Pastikan variabel $conn dari koneksi.php terbaca
    if (!$conn) {
        die("Koneksi Database Gagal: " . mysqli_connect_error());
    }

    // Prepared Statement
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi Hash Bcrypt
        if (password_verify($password, $row['password'])) {
            
            // Login Berhasil - Cegah Session Hijacking
            session_regenerate_id(true);

            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role']; 
            $_SESSION['status']   = "login";

            header("Location: dashboard.php");
            exit();
        }
    }
    
    // Jika gagal, balik ke login dengan pesan error
    $_SESSION['error_msg'] = "Username atau Password salah!";
    header("Location: login.php");
    exit();

} else {
    header("Location: login.php");
    exit();
}
?>