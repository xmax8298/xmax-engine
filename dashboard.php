<?php
session_start();
include 'koneksi.php';

// Proteksi Login
if (!isset($_SESSION['status']) || $_SESSION['status'] !== "login") {
    header("Location: login.php");
    exit();
}

$my_username = htmlspecialchars($_SESSION['username']);
$my_role     = strtolower($_SESSION['role']);

// Fitur Tambah User (Hanya Superadmin)
if (isset($_POST['tambah']) && $my_role === 'superadmin') {
    $user = trim(mysqli_real_escape_string($conn, $_POST['username']));
    $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];

    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $user, $pass, $role);
    
    if ($stmt->execute()) {
        echo "<script>alert('User Baru Berhasil Ditambahkan!'); window.location='dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal! Username mungkin sudah ada.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>XMAX - Panel</title>
    <style>
        body { background: #0b0b0b; color: #fff; font-family: 'Inter', sans-serif; padding: 40px; }
        .card { background: #1a1a1a; padding: 25px; border-radius: 12px; border-left: 5px solid #f39c12; margin-bottom: 20px; }
        .btn { padding: 10px 20px; background: #f39c12; color: #000; text-decoration: none; border-radius: 6px; font-weight: bold; border: none; cursor: pointer; }
        input, select { padding: 12px; margin: 8px 0; width: 100%; display: block; background: #222; border: 1px solid #444; color: #fff; }
    </style>
</head>
<body>
    <div class="card">
        <h2>Dashboard</h2>
        <p>User: <b><?php echo $my_username; ?></b> | Role: <span style="color:#f39c12;"><?php echo strtoupper($my_role); ?></span></p>
        <a href="logout.php" class="btn" style="background:#e74c3c; color:#fff;">Logout</a>
    </div>

    <?php if ($my_role === 'superadmin'): ?>
   <div class="action-box">
    <h3 style="color: #f0d481;">➕ TAMBAH USER/ADMIN BARU</h3>
    <form method="POST">
        <input type="text" name="username" placeholder="Username Baru" required class="input-style">
        <input type="password" name="password" placeholder="Password Baru" required class="input-style">
        
        <select name="role" class="input-style">
            <option value="superadmin">Super Admin</option>
            <option value="admin">Admin</option>
            <option value="moderator">Moderator</option>
            <option value="user">User Biasa</option>
        </select>
        
        <button type="submit" name="tambah" class="btn-action">SIMPAN USER</button>
    </form>
</div>
    <?php endif; ?>
</body>
</html>