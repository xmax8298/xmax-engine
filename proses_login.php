<?php
session_start();
include 'koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = isset($_POST['username']) ? trim($_POST['username']) : '';
    $password = isset($_POST['password']) ? trim($_POST['password']) : '';

    if (!$conn) {
        die("Koneksi Database Gagal: " . mysqli_connect_error());
    }

    if (empty($username) || empty($password)) {
        echo "<script>alert('Username dan Password tidak boleh kosong!'); window.location.href='login.php';</script>";
        exit();
    }

    $stmt = $conn->prepare("SELECT id, username, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        
        // ==============================================================================
        // 🔴 MODE DEBUGGING NYALA (Langsung ketahuan errornya di mana)
        // ==============================================================================
        /*echo "<div style='background:#111; color:#0f0; padding:20px; font-family:monospace; font-size:16px;'>";
        echo "<h3>--- XMAX SECURITY DEBUG ---</h3>";
        echo "1. Username yang lu ketik : <b>'" . htmlspecialchars($username) . "'</b><br>";
        echo "2. Password yang lu ketik : <b>'" . htmlspecialchars($password) . "'</b><br>";
        echo "3. Password di Database   : <b>'" . htmlspecialchars($row['password']) . "'</b><br>";
        echo "4. Panjang karakter di DB : <b>" . strlen($row['password']) . "</b> (Kalau Bcrypt HARUS 60!)<br>";
        echo "</div>";*/
        die("<br><b>Cek layarnya bro!</b> Kalau udah tau bedanya, kasih tau gue tulisan di layar lu apa!");
        // ==============================================================================

        if (password_verify($password, $row['password']) || $password === $row['password']) {
            session_regenerate_id(true);
            $_SESSION['user_id']  = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role']     = $row['role']; 
            $_SESSION['status']   = "login";

            echo "<script>
                    alert('Login Berhasil!');
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
} else {
    header("Location: login.php");
    exit();
}
?>