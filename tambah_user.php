<?php
// Session start HARUS di baris paling pertama sebelum include apapun
session_start();
include 'koneksi.php';

// 1. Proteksi Akses: Cek Login
// Kalau belum login, lempar ke login.php (BUKAN dashboard biar nggak looping)
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit();
}

// 2. Proteksi Level: Hanya Superadmin yang boleh akses halaman ini
if ($_SESSION['role'] !== 'superadmin') {
    echo "<script>alert('Akses Ditolak! Anda bukan Superadmin.'); window.location='dashboard.php';</script>";
    exit();
}

// 3. Logika Tambah User
if (isset($_POST['tambah'])) {
    // Ambil data dan bersihkan
    $user = trim(mysqli_real_escape_string($conn, $_POST['username']));
    // JANGAN escape password, langsung hash saja
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT); 
    $role = $_POST['role'];

    // Cek apakah username sudah ada (Cegah Duplicate)
    $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $cek->bind_param("s", $user);
    $cek->execute();
    $hasilCek = $cek->get_result();

    if ($hasilCek->num_rows > 0) {
        echo "<script>alert('Error: Username sudah dipakai orang lain!');</script>";
    } else {
        // Gunakan Prepared Statement untuk Insert
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user, $pass, $role);
        
        if ($stmt->execute()) {
            echo "<script>alert('User Berhasil Ditambah!'); window.location='dashboard.php';</script>";
            exit();
        } else {
            echo "<script>alert('Gagal menambah user!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Admin - XMAXengine</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <style>
        /* Tambahan CSS agar form tetap cantik dan rapi */
        .action-box { max-width: 500px; margin: 50px auto; padding: 30px; background: #111; border-radius: 12px; border: 1px solid #333; }
        .input-group { margin-bottom: 20px; }
        .input-style { 
            width: 100%; 
            padding: 12px 15px; 
            background: #161616; 
            border: 1px solid #444; 
            color: #fff; 
            border-radius: 8px; 
            box-sizing: border-box; 
            outline: none;
        }
        .input-style:focus { border-color: #f0d481; }
        .btn-action { 
            width: 100%; 
            padding: 14px; 
            border: none; 
            border-radius: 8px; 
            background: linear-gradient(to bottom, #f9e19d, #b58d28); 
            color: #000; 
            font-weight: 800; 
            cursor: pointer; 
            text-transform: uppercase; 
            transition: 0.3s;
        }
        .btn-action:hover { opacity: 0.9; transform: scale(1.01); }
        .eye-btn { position: absolute; right: 15px; top: 38px; cursor: pointer; color: #f0d481; font-size: 1.2rem; }
    </style>
</head>
<body>

    <div class="main-content">
        <div class="container">
            <div class="action-box">
                <h2 style="color: #f0d481; margin-top: 0;">➕ TAMBAH USER BARU</h2>
                <p style="color: #888; font-size: 0.9rem; margin-bottom: 25px;">Daftarkan tim admin atau moderator baru di sini.</p>
                
                <form method="POST">
                    <div class="input-group">
                        <label style="color: #aaa; font-size: 0.8rem; display: block; margin-bottom: 8px;">Username</label>
                        <input type="text" name="username" placeholder="Masukkan Username" required class="input-style">
                    </div>

                    <div class="input-group" style="position: relative;">
                        <label style="color: #aaa; font-size: 0.8rem; display: block; margin-bottom: 8px;">Password</label>
                        <input type="password" name="password" id="passInput" placeholder="Masukkan Password" required class="input-style">
                        <span id="mata" class="eye-btn">👁️</span>
                    </div>

                    <div class="input-group">
                        <label style="color: #aaa; font-size: 0.8rem; display: block; margin-bottom: 8px;">Level Akses (Role)</label>
                        <select name="role" class="input-style">
                            <option value="admin">Admin (Editor)</option>
                            <option value="moderator">Moderator (Viewer)</option>
                        </select>
                    </div>

                    <button type="submit" name="tambah" class="btn-action">SIMPAN USER</button>
                    <a href="dashboard.php" style="display:block; text-align:center; margin-top:20px; color:#f0d481; text-decoration:none; font-size: 0.9rem;">← Kembali ke Dashboard</a>
                </form>
            </div>
        </div>
    </div>

    <script>
        const mata = document.querySelector('#mata');
        const passInput = document.querySelector('#passInput');

        mata.addEventListener('click', function() {
            // Toggle tipe input antara password dan text
            const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passInput.setAttribute('type', type);
            
            // Ganti icon biar ada feedback visual
            this.textContent = type === 'password' ? '👁️' : '🙈';
        });
    </script>
</body>
</html>