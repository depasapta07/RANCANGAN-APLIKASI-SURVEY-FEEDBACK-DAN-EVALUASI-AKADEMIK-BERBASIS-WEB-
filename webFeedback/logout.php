<?php
// Mulai session
session_start();

// Hapus semua variabel session
session_unset();

// Hancurkan session secara keseluruhan
session_destroy();

// Redirect kembali ke halaman login utama
header("Location: login.php");
exit;
?>