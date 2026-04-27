<?php
session_start();
$_SESSION['test'] = "OKE";
echo "Session tersimpan: " . $_SESSION['test'];
echo "<br>ID Session: " . session_id();
echo "<br>Simpan Path: " . session_save_path();
?>