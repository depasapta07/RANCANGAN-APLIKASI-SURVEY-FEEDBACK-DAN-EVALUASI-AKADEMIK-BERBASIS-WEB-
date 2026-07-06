<?php
session_start();
include 'db.php';

// Pastikan hanya admin yang bisa mengakses
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Cek apakah ada ID yang dikirim dari URL
if (isset($_GET['id'])) {
    $id_mahasiswa = $_GET['id'];

    // Siapkan perintah DELETE ke database
    $sql = "DELETE FROM mahasiswa WHERE id_mahasiswa = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        // "i" berarti integer (karena ID berbentuk angka)
        $stmt->bind_param("i", $id_mahasiswa);

        if ($stmt->execute()) {
    // Berhasil? Langsung balik ke tabel tanpa pop-up tambahan
    header("Location: admin-mahasiswa.php");
    exit();
} else {
    // Gagal? Lu bisa balikkin juga atau kasih tanda eror di URL
    header("Location: admin-mahasiswa.php?status=error");
    exit();
}
        $stmt->close();
    }
} else {
    // Jika file diakses langsung tanpa ID
    header("Location: admin-mahasiswa.php");
}
?>