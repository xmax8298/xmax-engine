<?php
$host = "localhost";
$user = "xmax_root"; // Ganti dengan user database lu
$pass = "uI@850f9#Sat3%ay";     // Ganti dengan password database lu
$db = "xmax_db";
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>