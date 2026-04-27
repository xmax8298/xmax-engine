<?php
// 1. Cek session di paling atas
session_start();

// Jika user SUDAH login, langsung lempar ke dashboard agar tidak bisa akses halaman login lagi
// TAPI, kita pakai pengecekan yang ketat agar tidak looping
if (isset($_SESSION['status']) && $_SESSION['status'] === "login") {
    header("Location: dashboard.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - XMAXengine</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        /* Tambahin sedikit CSS biar posisi mata nggak berantakan */
        .password-container { position: relative; width: 100%; }
        .input-style { width: 100%; padding-right: 45px; box-sizing: border-box; }
        #togglePassword { 
            position: absolute; 
            right: 15px; 
            top: 50%; 
            transform: translateY(-50%); 
            cursor: pointer; 
            user-select: none;
        }
    </style>
</head>
<body>

    <div class="login-container">
        <div class="login-box">
            <div class="close-btn">&times;</div>
            <h2>MASUK</h2>
            
            <form action="proses_login.php" method="POST">
                <div class="input-group">
                    <label>Nama Pengguna</label>
                    <input type="text" name="username" placeholder="Nama Pengguna" required>
                </div>
                
                <div class="input-group">
                    <label>Kata Sandi</label>
                    <div class="password-container">
                        <input type="password" name="password" id="password" placeholder="Masukkan Password" required class="input-style">
                        <span id="togglePassword">👁️</span>
                    </div>
                </div>

                <div class="forgot-password">
                    <a href="#">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="btn-login">MASUK</button>
            </form>

            <p class="register-text">Belum punya akun? <a href="#">Daftar Sekarang</a></p>
        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            // Toggle tipe input
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Ganti icon mata
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>