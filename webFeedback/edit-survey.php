<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_survey       = $_POST['id_survey'];
    $judul_survey    = $_POST['judul_survey'];
    $deskripsi       = $_POST['deskripsi'];
    $tanggal_mulai   = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $target_prodi    = $_POST['target_prodi']; // Ambil input prodi edit
    $status          = $_POST['status'];

    // UPDATE QUERY: Tambahkan target_prodi
    $sql = "UPDATE surveys SET judul_survey = ?, deskripsi = ?, tanggal_mulai = ?, tanggal_selesai = ?, target_prodi = ?, status = ? WHERE id_survey = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssi", $judul_survey, $deskripsi, $tanggal_mulai, $tanggal_selesai, $target_prodi, $status, $id_survey);

    if ($stmt->execute()) {
        header("Location: admin-survey.php?status=edit_sukses");
        exit();
    } else {
        echo "Gagal memperbarui: " . $conn->error;
    }
}
?>