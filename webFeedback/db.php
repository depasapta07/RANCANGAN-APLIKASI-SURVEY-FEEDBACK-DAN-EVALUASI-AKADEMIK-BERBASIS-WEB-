<?php
$host = 'localhost';
$user = 'root'; // Ganti dengan username MySQL Anda (biasanya 'root' di XAMPP)
$password = ''; // Ganti dengan password MySQL Anda (kosongkan jika pakai XAMPP default)

// Nama database disesuaikan dengan gambar yang kamu kirim (feedback)
$dbname = 'feedback'; 

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>