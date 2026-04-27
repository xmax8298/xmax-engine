<?php
session_start();
include 'koneksi.php';

// Matikan error reporting biar bersih
error_reporting(0);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!$conn) {
        die("Koneksi Database Gagal!");
    }

    // Ambil data user
    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // Verifikasi: Cek Bcrypt atau Teks Biasa
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            
            // Login Berhasil
            session_regenerate_id(true);
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role']; 
            $_SESSION['status']   = "login";

            echo "<script>
                    alert('Login Berhasil! Selamat datang " . htmlspecialchars($row['username']) . "');
                    window.location.href='dashboard.php';
                  </script>";
            exit();
        } else {
            $error = "Password Salah!";
        }
    } else {
        $error = "Username Tidak Ditemukan!";
    }
    
    // Balik ke login kalau gagal
    echo "<script>
            alert('" . $error . "');
            window.location.href='login.php';
          </script>";
    exit();
}
?>