<?php
session_start();
include 'koneksi.php';

// 1. Proteksi Akses Ketat
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit();
}

// Ambil data session agar variabelnya pendek & aman
$my_username = htmlspecialchars($_SESSION['username']);
$my_role     = strtolower($_SESSION['role']); // Pakai strtolower biar gak bentrok S besar/kecil

// 2. Logika Tambah User (Jika form di bawah dikirim)
if (isset($_POST['tambah'])) {
    // Cek lagi role-nya secara sistem (Double Protection)
    if ($my_role !== 'superadmin') {
        echo "<script>alert('Akses Ilegal!'); window.location='dashboard.php';</script>";
        exit();
    }

    $user = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $cek = $conn->prepare("SELECT id FROM users WHERE username = ?");
    $cek->bind_param("s", $user);
    $cek->execute();
    if ($cek->get_result()->num_rows > 0) {
        echo "<script>alert('Error: Username sudah terdaftar!');</script>";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $user, $pass, $role);
        if ($stmt->execute()) {
            echo "<script>alert('User Berhasil Ditambah!'); window.location='dashboard.php';</script>";
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - XMAXengine</title>
    <link rel="stylesheet" href="dashboard-style.css">
    <style>
        /* Gaya tambahan agar lebih pro */
        body { background-color: #0d0d0d; color: #eee; font-family: 'Inter', sans-serif; }
        .container { max-width: 900px; margin: 30px auto; padding: 20px; }
        .user-profile { background: #161616; padding: 20px; border-radius: 12px; border-left: 5px solid #f0d481; margin-bottom: 25px; }
        .action-box { background: #161616; padding: 25px; border-radius: 12px; border: 1px solid #333; margin-top: 20px; }
        /* ... CSS lu sisanya sudah oke ... */
    </style>
</head>
<body>

<div class="container">
    <div class="user-profile">
        <h2>Selamat Datang, <span style="color: #f0d481;"><?php echo $my_username; ?></span></h2>
        <p>Akses Level: <span class="badge"><?php echo strtoupper($my_role); ?></span></p>
    </div>

    <div class="menu-container">
        <a href="dashboard.php" class="btn-menu">🏠 Dashboard</a>
        <?php if ($my_role === 'superadmin'): ?>
            <a href="manage_users.php" class="btn-menu">👥 Kelola User</a>
            <a href="log_aktivitas.php" class="btn-menu">📜 Log Sistem</a>
        <?php endif; ?>
        <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin mau keluar?')">🚪 Keluar</a>
    </div>

    <?php if ($my_role === 'superadmin'): ?>
    <div class="action-box">
        <h3 style="color: #f0d481;">➕ TAMBAH ADMIN BARU</h3>
        <form method="POST">
            <input type="text" name="username" placeholder="Username Baru" required class="input-style">
            <input type="password" name="password" placeholder="Password Baru" required class="input-style">
            <select name="role" class="input-style">
                <option value="admin">Admin</option>
                <option value="moderator">Moderator</option>
            </select>
            <button type="submit" name="tambah" class="btn-action">SIMPAN USER</button>
        </form>
    </div>
    <?php else: ?>
    <div class="action-box">
        <h3>INFO PANEL</h3>
        <p style="color: #888;">Anda login sebagai <b><?php echo $my_role; ?></b>. Anda hanya memiliki akses terbatas pada sistem ini.</p>
    </div>
    <?php endif; ?>
</div>

</body>
</html>