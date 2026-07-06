<?php
session_start();
include 'db.php';

if (isset($_GET['id'])) {
    $id_survey = $_GET['id'];

    // 1. HAPUS ANAK TABEL (PERTANYAAN) TERLEBIH DAHULU
    $sql_pertanyaan = "DELETE FROM pertanyaan WHERE id_survey = ?";
    $stmt_p = $conn->prepare($sql_pertanyaan);
    $stmt_p->bind_param("i", $id_survey); // Mengikat parameter untuk pertanyaan
    $stmt_p->execute();
    $stmt_p->close(); // Langsung tutup biar tidak bentrok

    // 2. HAPUS INDUK TABEL (SURVEYS)
    $sql_survey = "DELETE FROM surveys WHERE id_survey = ?";
    $stmt_s = $conn->prepare($sql_survey);
    $stmt_s->bind_param("i", $id_survey); // Mengikat parameter untuk survey

    if ($stmt_s->execute()) {
        $stmt_s->close();
        header("Location: admin-survey.php?status=hapus_sukses");
        exit();
    } else {
        echo "Gagal menghapus data survey: " . $conn->error;
    }
} else {
    header("Location: admin-survey.php");
    exit();
}
?>