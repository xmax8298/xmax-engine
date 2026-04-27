<?php
session_start();
include 'koneksi.php';

// Aktifkan error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!$conn) {
        die("Koneksi Database Gagal!");
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // CEK PASSWORD: Coba pakai Bcrypt dulu, kalau gagal coba teks biasa (biar user lama bisa masuk)
        if (password_verify($password, $row['password']) || $password === $row['password']) {
            
            session_regenerate_id(true);
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role']; 
            $_SESSION['status']   = "login";

            echo "<script>
                    alert('Login Berhasil sebagai " . $row['role'] . "!');
                    window.location.href='dashboard.php';
                  </script>";
            exit();
        } else {
            $_SESSION['error_msg'] = "Password Salah!";
        }
    } else {
        $_SESSION['error_msg'] = "Username Tidak Ditemukan!";
    }
    
    echo "<script>
            alert('" . $_SESSION['error_msg'] . "');
            window.location.href='login.php';
          </script>";
    exit();
}
