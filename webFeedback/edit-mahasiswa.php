<?php
session_start();
include 'db.php';

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah data dikirim melalui method POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Ambil data dari form modal edit
    $id_mahasiswa = $_POST['id_mahasiswa'];
    $nama_mhs     = $_POST['nama_mhs'];
    $npm          = $_POST['npm'];
    $prodi        = $_POST['prodi'];
    $password     = $_POST['password']; // Menangkap data password dari modal

    // Validasi dasar agar ID tidak kosong
    if (!empty($id_mahasiswa)) {
        
        /* QUERY UPDATE:
          Pastikan susunan nama kolomnya persis seperti di database kamu:
          nama_mhs, npm, prodi, password
        */
        $sql = "UPDATE mahasiswa SET nama_mhs = ?, npm = ?, prodi = ?, password = ? WHERE id_mahasiswa = ?";
        
        $stmt = $conn->prepare($sql);
        
        // "ssssi" -> 4 string (nama, npm, prodi, password) dan 1 integer (id_mahasiswa)
        $stmt->bind_param("ssssi", $nama_mhs, $npm, $prodi, $password, $id_mahasiswa);
        
        if ($stmt->execute()) {
            // Jika berhasil, redirect balik ke halaman utama admin-mahasiswa
            header("Location: admin-mahasiswa.php?status=sukses");
            exit();
        } else {
            // Jika gagal eksekusi query
            echo "Gagal memperbarui data di database: " . $conn->error;
        }
        
    } else {
        echo "ID Mahasiswa kosong atau tidak terbaca.";
    }
} else {
    // Jika mencoba akses langsung file ini tanpa post data
    header("Location: admin-mahasiswa.php");
    exit();
}
?>