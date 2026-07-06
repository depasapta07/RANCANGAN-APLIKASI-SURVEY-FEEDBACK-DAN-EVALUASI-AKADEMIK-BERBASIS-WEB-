<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $judul_survey    = $_POST['judul_survey'];
    $deskripsi       = $_POST['deskripsi'];
    $tanggal_mulai   = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $target_prodi    = $_POST['target_prodi']; // Tangkap input prodi
    
    $status_form = $_POST['status']; 
if ($status_form == 'Aktif') {
    $status = 'aktif';
} elseif ($status_form == 'Draft') {
    $status = 'draft';
} else {
    $status = 'non-aktif';
}
    // UPDATE QUERY: Tambahkan target_prodi
    $sql = "INSERT INTO surveys (judul_survey, deskripsi, tanggal_mulai, tanggal_selesai, target_prodi, status) VALUES (?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $judul_survey, $deskripsi, $tanggal_mulai, $tanggal_selesai, $target_prodi, $status);
    
    if ($stmt->execute()) {
        header("Location: admin-survey.php?status=sukses");
        exit();
    } else {
        echo "Gagal menyimpan: " . $conn->error;
    }
}
?>