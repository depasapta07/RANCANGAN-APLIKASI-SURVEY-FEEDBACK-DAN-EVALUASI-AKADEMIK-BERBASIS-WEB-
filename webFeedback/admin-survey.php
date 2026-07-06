<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// 1. Hitung total mahasiswa
$sql_mhs = "SELECT COUNT(*) as total FROM mahasiswa";
$res_mhs = $conn->query($sql_mhs);
$total_mahasiswa = ($res_mhs) ? $res_mhs->fetch_assoc()['total'] : 0;

// 2. Ambil semua data dari tabel surveys untuk ditampilkan di tabel kuesioner
$sql_survey = "SELECT * FROM surveys ORDER BY id_survey DESC";
$result_survey = $conn->query($sql_survey);

// 3. QUERY BARU: Hitung jumlah survey yang sedang aktif
$sql_aktif = "SELECT COUNT(*) as total_aktif FROM surveys WHERE status = 'aktif'";
$res_aktif = $conn->query($sql_aktif);
$total_aktif = ($res_aktif) ? $res_aktif->fetch_assoc()['total_aktif'] : 0;

//4. QUERY Hitung Survey Draft & Ditutup
$sql_draft = "SELECT COUNT(*) as total_draft FROM surveys WHERE status = 'draft'";
$res_draft = $conn->query($sql_draft);
$total_draft = ($res_draft) ? $res_draft->fetch_assoc()['total_draft'] : 0;

$sql_closed = "SELECT COUNT(*) as total_closed FROM surveys WHERE status = 'non-aktif'";
$res_closed = $conn->query($sql_closed);
$total_closed = ($res_closed) ? $res_closed->fetch_assoc()['total_closed'] : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Halaman Admin</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@2.2.0/fonts/remixicon.css" />
  <link rel="stylesheet" href="https://unpkg.com/css-pro-layout@1.1.0/dist/css/css-pro-layout.css" />
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&amp;display=swap" />
  <style>
    .layout {
      z-index: 1;
    }

    .layout .header {
      display: flex;
      align-items: center;
      padding: 20px;
    }

    .layout .content {
      padding: 12px 50px;
      display: flex;
      flex-direction: column;
    }

    .layout .footer {
      text-align: center;
      margin-top: auto;
      margin-bottom: 20px;
      padding: 20px;
    }

    .sidebar {
      color: #000;
      overflow-x: hidden !important;
      position: relative;
    }

    .sidebar::-webkit-scrollbar-thumb {
      border-radius: 4px;
    }

    .sidebar:hover::-webkit-scrollbar-thumb {
      background-color: #1a4173;
    }

    .sidebar::-webkit-scrollbar {
      width: 6px;
      background-color: #0c1e35;
    }

    .sidebar .image-wrapper {
      overflow: hidden;
      position: absolute;
      top: 0;
      right: 0;
      bottom: 0;
      left: 0;
      z-index: 1;
      display: none;
    }

    .sidebar .image-wrapper > img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      object-position: center;
    }

    .sidebar.has-bg-image .image-wrapper {
      display: block;
    }

    .sidebar .sidebar-layout {
      height: auto;
      min-height: 100%;
      display: flex;
      flex-direction: column;
      position: relative;
      backdrop-filter: blur(15px);
      border: 1px solid;
      border-top: transparent;
      border-left: transparent;
      border-bottom: transparent;
      background-color: rgba(55, 55, 55, 0.1);
      z-index: 2;
    }

    .sidebar .sidebar-layout .sidebar-header {
      height: 100px;
      min-height: 100px;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .sidebar .sidebar-layout .sidebar-header>span {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    .sidebar .sidebar-layout .sidebar-content {
      flex-grow: 1;
      padding: 10px 0;
    }

    .sidebar .sidebar-layout .sidebar-footer {
      height: 230px;
      min-height: 230px;
      display: flex;
      align-items: center;
      padding: 0 20px;
    }

    .sidebar .sidebar-layout .sidebar-footer>span {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
    }

    @keyframes swing {
      0%,
      30%,
      50%,
      70%,
      100% {
        transform: rotate(0deg);
      }

      10% {
        transform: rotate(10deg);
      }

      40% {
        transform: rotate(-10deg);
      }

      60% {
        transform: rotate(5deg);
      }

      80% {
        transform: rotate(-5deg);
      }
    }

    .layout .sidebar .menu ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
    }

    .layout .sidebar .menu .menu-header {
      font-weight: 600;
      padding: 10px 25px;
      font-size: 0.8em;
      letter-spacing: 2px;
      transition: opacity 0.3s;
      opacity: 0.5;
    }

    .layout .sidebar .menu .menu-item a {
      display: flex;
      align-items: center;
      height: 50px;
      padding: 0 20px;
      color: #000;
    }

    .layout .sidebar .menu .menu-item a .menu-icon {
      font-size: 1.2rem;
      width: 35px;
      min-width: 35px;
      height: 35px;
      line-height: 35px;
      text-align: center;
      display: inline-block;
      margin-right: 10px;
      border-radius: 2px;
      transition: color 0.3s;
    }

    .layout .sidebar .menu .menu-item a .menu-icon i {
      display: inline-block;
    }

    .layout .sidebar .menu .menu-item a .menu-title {
      font-size: 0.9em;
      overflow: hidden;
      text-overflow: ellipsis;
      white-space: nowrap;
      flex-grow: 1;
      transition: color 0.3s;
    }

    .layout .sidebar .menu .menu-item a .menu-prefix,
    .layout .sidebar .menu .menu-item a .menu-suffix {
      display: inline-block;
      padding: 5px;
      opacity: 1;
      transition: opacity 0.3s;
    }

    .layout .sidebar .menu .menu-item a:hover .menu-title {
      color: #000;
    }

    .layout .sidebar .menu .menu-item a:hover .menu-icon {
      color: #000;
    }

    .layout .sidebar .menu .menu-item a:hover .menu-icon i {
      animation: swing ease-in-out 0.5s 1 alternate;
    }

    .layout .sidebar .menu .menu-item a:hover::after {
      border-color: #000 !important;
    }

    .layout .sidebar .menu .menu-item.sub-menu {
      position: relative;
    }

    .layout .sidebar .menu .menu-item.sub-menu>a::after {
      content: "";
      transition: transform 0.3s;
      border-right: 2px solid currentcolor;
      border-bottom: 2px solid currentcolor;
      width: 5px;
      height: 5px;
      transform: rotate(-45deg);
    }

    .layout .sidebar .menu .menu-item.sub-menu>.sub-menu-list {
      padding-left: 20px;
      display: none;
      overflow: hidden;
      z-index: 999;
    }

    .layout .sidebar .menu .menu-item.sub-menu.open>a {
      color: #000;
    }

    .layout .sidebar .menu .menu-item.sub-menu.open>a::after {
      transform: rotate(45deg);
    }

    .layout .sidebar .menu .menu-item.active>a .menu-title {
      color: #000;
    }

    .layout .sidebar .menu .menu-item.active>a::after {
      border-color: #000;
    }

    .layout .sidebar .menu .menu-item.active>a .menu-icon {
      color: #000;
    }

    .layout .sidebar .menu>ul>.sub-menu>.sub-menu-list {
      background-color: #0b1a2c;
    }

    .layout .sidebar .menu.icon-shape-circle .menu-item a .menu-icon,
    .layout .sidebar .menu.icon-shape-rounded .menu-item a .menu-icon,
    .layout .sidebar .menu.icon-shape-square .menu-item a .menu-icon {
      background-color: #0b1a2c;
    }

    .layout .sidebar .menu.icon-shape-circle .menu-item a .menu-icon {
      border-radius: 50%;
    }

    .layout .sidebar .menu.icon-shape-rounded .menu-item a .menu-icon {
      border-radius: 4px;
    }

    .layout .sidebar .menu.icon-shape-square .menu-item a .menu-icon {
      border-radius: 0;
    }

    .layout .sidebar:not(.collapsed) .menu>ul>.menu-item.sub-menu>.sub-menu-list {
      visibility: visible !important;
      position: static !important;
      transform: translate(0, 0) !important;
    }

    .layout .sidebar.collapsed .menu>ul>.menu-header {
      opacity: 0;
    }

    .layout .sidebar.collapsed .menu>ul>.menu-item>a .menu-prefix,
    .layout .sidebar.collapsed .menu>ul>.menu-item>a .menu-suffix {
      opacity: 0;
    }

    .layout .sidebar.collapsed .menu>ul>.menu-item.sub-menu>a::after {
      content: "";
      width: 5px;
      height: 5px;
      background-color: currentcolor;
      border-radius: 50%;
      display: inline-block;
      position: absolute;
      right: 10px;
      top: 50%;
      border: none;
      transform: translateY(-50%);
    }

    .layout .sidebar.collapsed .menu>ul>.menu-item.sub-menu>a:hover::after {
      background-color: #000;
    }

    .layout .sidebar.collapsed .menu>ul>.menu-item.sub-menu>.sub-menu-list {
      transition: none !important;
      width: 200px;
      margin-left: 3px !important;
      border-radius: 4px;
      display: block !important;
    }

    .layout .sidebar.collapsed .menu>ul>.menu-item.active>a::after {
      background-color: #000;
    }

    .layout .sidebar.has-bg-image .menu.icon-shape-circle .menu-item a .menu-icon,
    .layout .sidebar.has-bg-image .menu.icon-shape-rounded .menu-item a .menu-icon,
    .layout .sidebar.has-bg-image .menu.icon-shape-square .menu-item a .menu-icon {
      background-color: rgba(11, 26, 44, 0.6);
    }

    .layout .sidebar.has-bg-image:not(.collapsed) .menu>ul>.sub-menu>.sub-menu-list {
      background-color: rgba(11, 26, 44, 0.6);
    }

    .layout.rtl .sidebar .menu .menu-item a .menu-icon {
      margin-left: 10px;
      margin-right: 0;
    }

    .layout.rtl .sidebar .menu .menu-item.sub-menu>a::after {
      transform: rotate(135deg);
    }

    .layout.rtl .sidebar .menu .menu-item.sub-menu>.sub-menu-list {
      padding-left: 0;
      padding-right: 20px;
    }

    .layout.rtl .sidebar .menu .menu-item.sub-menu.open>a::after {
      transform: rotate(45deg);
    }

    .layout.rtl .sidebar.collapsed .menu>ul>.menu-item.sub-menu a::after {
      right: auto;
      left: 10px;
    }

    .layout.rtl .sidebar.collapsed .menu>ul>.menu-item.sub-menu>.sub-menu-list {
      margin-left: -3px !important;
    }

    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      height: 100vh;
      font-family: "Poppins", sans-serif;
      background-color: #f4f7f6; /* Warna background diubah sedikit agar card putih lebih menonjol */
      color: #000;
      font-size: 0.9rem;
    }

    a {
      text-decoration: none;
    }

    @media (max-width: 576px) {
      #btn-collapse {
        display: none;
      }
    }

    .layout .sidebar .pro-sidebar-logo {
      display: flex;
      align-items: center;
    }

    .layout .sidebar .pro-sidebar-logo>div {
      width: 35px;
      min-width: 35px;
      height: 35px;
      min-height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 8px;
      color: white;
      font-size: 24px;
      font-weight: 700;
      background-color: #ff8100;
      margin-right: 10px;
    }

    .layout .sidebar .pro-sidebar-logo>h5 {
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      font-size: 20px;
      line-height: 30px;
      transition: opacity 0.3s;
      opacity: 1;
    }

    .layout .sidebar .footer-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      font-size: 0.8em;
      padding: 20px 0;
      border-radius: 8px;
      width: 180px;
      min-width: 190px;
      margin: 0 auto;
      background-color: #162d4a;
    }

    .layout .sidebar .footer-box img.react-logo {
      width: 40px;
      height: 40px;
      margin-bottom: 10px;
    }

    .layout .sidebar .footer-box a {
      color: #fff;
      font-weight: 600;
      margin-bottom: 10px;
    }

    .layout .sidebar .sidebar-collapser {
      transition: left, right, 0.3s;
      position: fixed;
      left: 260px;
      top: 40px;
      width: 20px;
      height: 20px;
      border-radius: 50%;
      background-color: #00829f;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.2em;
      transform: translateX(50%);
      z-index: 111;
      cursor: pointer;
      color: white;
      box-shadow: 1px 1px 4px #0c1e35;
    }

    .layout .sidebar.collapsed .pro-sidebar-logo>h5 {
      opacity: 0;
    }

    .layout .sidebar.collapsed .footer-box {
      display: none;
    }

    .layout .sidebar.collapsed .sidebar-collapser {
      left: 60px;
    }

    .layout .sidebar.collapsed .sidebar-collapser i {
      transform: rotate(180deg);
    }

    .badge {
      display: inline-block;
      padding: 0.25em 0.4em;
      font-size: 75%;
      font-weight: 700;
      line-height: 1;
      text-align: center;
      white-space: nowrap;
      vertical-align: baseline;
      border-radius: 0.25rem;
      color: #fff;
      background-color: #6c757d;
    }

    .badge.primary {
      background-color: #ab2dff;
    }

    .badge.secondary {
      background-color: #079b0b;
    }

    .sidebar-toggler {
      position: fixed;
      right: 20px;
      top: 20px;
    }

    .social-links a {
      margin: 0 10px;
      color: #3f4750;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #ddd;
      padding: 10px;
      text-align: left;
    }

    th {
      background: #007BFF;
      color: white;
    }

    img {
      max-width: 50px;
      height: auto;
      border-radius: 5px;
    }

    td a {
      color: #007BFF;
      text-decoration: none;
    }

    td a:hover {
      text-decoration: underline;
    }

    /* Tampilan Welcome yang lebih menarik */
    .welcome-section {
      background: linear-gradient(135deg,rgb(144, 144, 140),rgb(55, 52, 50));
      padding: 40px;
      border-radius: 8px;
      text-align: center;
      color: #fff;
      margin-bottom: 20px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .welcome-section h1 {
      font-size: 36px;
      margin-bottom: 10px;
    }

    .welcome-section p {
      font-size: 18px;
    }

    /* CSS DAFTAR SURVEY */

        /* MAIN */
        .main {
            flex: 1;
            padding: 20px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .cards {
            display: flex;
            gap: 20px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            flex: 1;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h1 {
            font-size: 32px;
        }

        .buttons {
            margin: 20px 0;
            display: flex;
            gap: 20px;
        }

        .btn {
            flex: 1;
            padding: 15px;
            border: none;
            border-radius: 10px;
            background: #2f80ed;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background: #1c60c7;
        }

        /* TABLE */
        .table-container {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 12px;
            text-align: left;
        }

        th {
            background: #f0f0f0;
        }

        tr:nth-child(even) {
            background: #fafafa;
        }

        .action-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .edit {
            background: #2f80ed;
            color: white;
        }

        .delete {
            background: #e74c3c;
            color: white;
        }

        /* CARD ATAS ADMIN HOME*/
.cards {
    display: flex;
    gap: 20px;
    margin-bottom: 25px;
}

.card {
    flex: 1;
    background: white;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    text-align: left; /* Ditambahkan agar teks rata kiri sesuai gambar */
}

.card h3 {
    margin: 0;
    font-size: 14px;
    color: #777;
    font-weight: normal; /* Ditambahkan agar teks tidak terlalu tebal */
}

.card h1 {
    margin: 5px 0 0;
}

/* warna garis kiri */
.card.blue { border-left: 5px solid #4e73df; }
.card.green { border-left: 5px solid #1cc88a; }
.card.orange { border-left: 5px solid #f6c23e; }
.card.purple { border-left: 5px solid #9b59b6; }

/* WELCOME BOX */
.welcome {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.welcome h2 {
    margin-top: 0;
}

.welcome ul {
    margin-top: 15px;
}

.welcome ul li {
    margin-bottom: 10px;
    color: #444;
}

.check {
    color: green;
    font-weight: bold;
}

/* --- CSS BARU UNTUK HALAMAN KELOLA SURVEY --- */
.header-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}
.page-title h2 { font-size: 24px; font-weight: 600; color: #333; }
.page-title p { font-size: 13px; color: #777; margin-top: 2px; }

.btn-add-survey {
    background: #4e73df;
    color: white;
    padding: 10px 20px;
    border-radius: 8px;
    border: none;
    font-weight: 500;
    font-family: inherit;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: 0.2s;
}
.btn-add-survey:hover { background: #2e59d9; }

/* Struktur Grid Kartu Statistik */
.stats-cards {
    display: flex;
    gap: 15px; /* Sedikit dipersempit jaraknya biar muat 5 card */
    margin-bottom: 25px;
    flex-wrap: nowrap; /* Memaksa semua card tetap satu baris lurus */
    width: 100%;
}

.stat-card {
    flex: 1; /* Membuat setiap card membagi lebar secara rata dan adil */
    background: white;
    border-radius: 12px;
    padding: 15px 20px; /* Sedikit disesuaikan paddingnya */
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    border-left: 5px solid #4e73df;
    display: flex;
    justify-content: space-between;
    align-items: center;
    min-width: 0; /* Mencegah card melar akibat teks panjang */
}

.stat-card.green { border-left-color: #1cc88a; }
.stat-card.orange { border-left-color: #f6c23e; }
.stat-card.purple { border-left-color: #9b59b6; }

.stat-card h3 { 
    margin: 0; 
    font-size: 11px; /* Ukuran font disesuaikan biar pas satu baris */
    color: #777; 
    font-weight: 500;
    white-space: nowrap; /* Teks judul dipaksa memanjang tanpa patah bawah */
}.stat-card p { margin: 5px 0 0; font-size: 24px; font-weight: 700; color: #333; }
.stat-card i { font-size: 28px; color: #ccc; }

/* Wadah Putih Utama Tempat Tabel */
.survey-wrapper {
    background: white;
    border-radius: 15px;
    padding: 20px 25px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.filter-row {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

/* Area Scroll Internal Tabel */
.survey-table-container {
    flex-grow: 1;
    overflow-y: auto;
}

/* Replikasi Tabel Kotak-Kotak Sesuai Data Mahasiswa */
.table-survey { 
    width: 100%; 
    border-collapse: collapse;
    margin-top: 10px;
}
.table-survey th { 
    background-color: #f8f9fc !important; 
    color: #4e73df; 
    font-weight: 600; 
    padding: 15px 20px;
    border: 1px solid #e3e6f0;
    text-align: left;
}
.table-survey td { 
    padding: 15px 20px; 
    border: 1px solid #e3e6f0;
    color: #333;
    vertical-align: middle;
}
.table-survey tr:hover { background-color: #f8f9fc; }

.status-draft { 
    background: #fef3c7; 
    color: #b45309; 
    border: 1px solid #fcd34d; 
}
/* Badges & Buttons Aksi */
.status-badge {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
}
.status-active { background: #dcfce7; color: #16a34a; }
.status-closed { background: #fee2e2; color: #dc2626; }

.action-btn-group { display: flex; gap: 8px; }
.btn-survey-action {
    padding: 6px 12px; 
    border: none; 
    border-radius: 6px; 
    cursor: pointer;
    font-size: 0.85rem; 
    font-weight: 500; 
    display: inline-flex; 
    align-items: center; 
    gap: 5px; 
    transition: 0.2s;
    text-decoration: none !important; 
    color: inherit;
}
.btn-survey-action:hover {
    text-decoration: none !important;
}
.btn-survey-action.hasil { background: rgba(22, 163, 74, 0.1); color: #16a34a; }
.btn-survey-action.hasil:hover { background: #16a34a; color: white; }
.btn-survey-action.edit { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
.btn-survey-action.edit:hover { background: #4e73df; color: white; }
.btn-survey-action.delete { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
.btn-survey-action.delete:hover { background: #e74c3c; color: white; }

/* Tambahkan ini di dalam tag <style> kamu */
.stats-cards-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Baris pertama otomatis 3 kolom */
    gap: 20px;
    margin-bottom: 25px;
}

/* Trik CSS: Membuat card ke-4 dan ke-5 otomatis turun di bawahnya dengan ukuran lebar yang seimbang */
@media (min-width: 768px) {
    .stats-cards-grid .stat-card:nth-child(4) {
        grid-column: 1 / 2;
    }
    .stats-cards-grid .stat-card:nth-child(5) {
        grid-column: 2 / 3;
    }
}
  </style>
</head>

<body>
  <div class="layout has-sidebar fixed-sidebar fixed-header">
    <aside id="sidebar" class="sidebar break-point-sm has-bg-image">
      <a id="btn-collapse" class="sidebar-collapser"><i class="ri-arrow-left-s-line"></i></a>
      <div class="image-wrapper">
      </div>
      <div class="sidebar-layout">
        <div class="sidebar-header">
          <div class="pro-sidebar-logo">
            <img src="round1.png" width="50px" alt="" />
            <h5>Halaman Admin</h5>
          </div>
        </div>
        <div class="sidebar-content">
          <nav class="menu open-current-submenu">
            <ul>
              <li class="menu-header"><span style="font-weight: 700; color: #000;"> GENERAL </span></li>

              <li class="menu-item">
                <a href="admin.php">
                  <span class="menu-icon">
                    <i class="ri-home-2-line"></i>
                  </span>
                  <span class="menu-title">Dashboard</span>
                </a>
              </li>

              <li class="menu-item">
                <a href="admin-mahasiswa.php">
                  <span class="menu-icon">
                    <i class="ri-group-line"></i>
                  </span>
                  <span class="menu-title">Data Mahasiswa</span>
                </a>
              </li>

              <li class="menu-item">
                <a href="#"style="color: #4e73df; font-weight: bold;">
                  <span class="menu-icon"style="color: #4e73df;">
                    <i class="ri-file-list-3-line"></i>
                  </span>
                  <span class="menu-title">Kelola Survey</span>
                </a>
              </li>
              <li class="menu-item">
                <a href="admin-pertanyaan.php">
                  <span class="menu-icon">
                    <i class="ri-question-answer-line"></i>
                  </span>
                  <span class="menu-title">Kelola Pertanyaan</span>
                </a>
              </li>
              <li class="menu-item">
                <a href="index.php">
                  <span class="menu-icon">
                    <i class="ri-chat-new-line"></i>
                  </span>
                  <span class="menu-title">Membuat Pertanyaan</span>
                </a>
              </li>
              <li class="menu-item">
                <a href="index.php">
                  <span class="menu-icon">
                    <i class="ri-todo-line"></i>
                  </span>
                  <span class="menu-title">Kelola Jawaaban</span>
                </a>
              </li>
              <li class="menu-header" style="padding-top: 20px"><span> Sign Out </span></li>
              <li class="menu-item">
                <a href="login.php">
                  <span class="menu-icon">
                    <i class="ri-logout-box-fill"></i>
                  </span>
                  <span class="menu-title">Logout</span>
                </a>
              </li>
            </ul>
          </nav>
        </div>
        <!-- <div class="sidebar-footer">
          <div class="footer-box">
            
          </div>
        </div> -->
      </div>
    </aside>

    <!-- INI ADALAH AREA KONTEN UTAMA YANG BARU DITAMBAHKAN -->
    <main class="content main">
    
    <!-- Header: Judul Halaman & Tombol Tambah -->
    <div class="header-actions">
        <div class="page-title">
            <h2>Kelola Survey Akademik</h2>
            <p>Buat kuesioner baru, tentukan target responden, dan pantau umpan balik evaluasi.</p>
        </div>
        <!-- BERSIHKAN ONCLICK INLINE-NYA -->
<button type="button" class="btn-add-survey"><i class="ri-add-line"></i> Tambah Survey Baru</button>
    </div>

    <!-- 4 Kartu Statistik Senada dengan Dashboard Utama -->
    <!-- Bagian Kedua: Kartu Ringkasan Informasi -->
<!-- Cukup gunakan SATU pembungkus utama ini saja -->
<div class="stats-cards">
    
    <!-- CARD 1 -->
    <div class="stat-card">
        <div>
            <h3>Total Judul Survey</h3>
            <p><?= ($result_survey) ? $result_survey->num_rows : 0; ?></p>
        </div>
        <i class="ri-file-list-3-line"></i>
    </div>

    <!-- CARD 2 -->
    <div class="stat-card green">
        <div>
            <h3>Survey Aktif</h3>
            <p><?= $total_aktif; ?></p>
        </div>
        <i class="ri-checkbox-circle-line"></i>
    </div>

    <!-- CARD 3 -->
    <div class="stat-card orange">
        <div>
            <h3>Survey Draft</h3>
            <p><?= $total_draft; ?></p>
        </div>
        <i class="ri-edit-2-line"></i>
    </div>

    <!-- CARD 4 -->
    <div class="stat-card" style="border-left-color: #e74c3c;">
        <div>
            <h3>Survey Ditutup</h3>
            <p><?= $total_closed; ?></p>
        </div>
        <i class="ri-lock-line" style="color: #e74c3c;"></i>
    </div>

    <!-- CARD 5 -->
    <div class="stat-card purple">
        <div>
            <h3>Total Responden</h3>
            <p><?= $total_mahasiswa; ?></p>
        </div>
        <i class="ri-group-line"></i>
    </div>

</div>

    <!-- Kotak Putih Utama (Tempat Tabel Data) -->
    <div class="survey-wrapper">
        <!-- Live Search & Dropdown Filter -->
        <div class="filter-row">
            <div class="search-box">
                <!-- Input Pencarian Judul -->
<input type="text" id="surveyInput" onkeyup="filterSurvey()" placeholder="Cari judul survey...">

<!-- Dropdown Filter Status -->
<select id="statusFilter" onchange="filterSurvey()">
    <option value="">Semua Status</option>
    <option value="Aktif">Aktif</option>
    <option value="Draft">Draft</option>
    <option value="Ditutup">Ditutup</option>
</select>
            </div>
        </div>

        <!-- Tabel Konten Survey (Scrollable Internal) -->
        <div class="survey-table-container">
            <table class="table-survey">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Judul & Deskripsi Kuesioner</th>
                        <th>Periode Rilis</th>
                        <th>Target Responden</th>
                        <th>Status</th>
                        <th style="width: 260px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="surveyTableBody">
    <?php
    // Cek apakah ada data di tabel surveys
    if ($result_survey && $result_survey->num_rows > 0) {
        $no = 1;
        while ($row = $result_survey->fetch_assoc()) {
            // Menyesuaikan format tampilan tanggal rilis biar rapi
            $periode = date('d M Y', strtotime($row['tanggal_mulai'])) . " - " . date('d M Y', strtotime($row['tanggal_selesai']));
            
            // Menyesuaikan class warna badge berdasarkan status di database ('aktif' / 'non-aktif')
            $status_class = ($row['status'] == 'aktif') ? 'status-active' : 'status-closed';
            $status_text  = ($row['status'] == 'aktif') ? 'Aktif' : 'Ditutup';// --- CARI DAN GANTI LOGIKA BADGE STATUS DI DALAM WHILE LOOP TABEL KAMU ---
switch ($row['status']) {
    case 'aktif':
        $status_class = 'status-active';
        $status_text  = 'Aktif';
        break;
    case 'draft':
        $status_class = 'status-draft'; // Class CSS baru untuk draft
        $status_text  = 'Draft';
        break;
    default:
        $status_class = 'status-closed';
        $status_text  = 'Ditutup';
        break;
}
            ?>
            <tr>
                <td><?= $no++; ?></td>
               <!-- CARI TAG TD UNTUK JUDUL & DESKRIPSI KUESIONER, LALU GANTI MENJADI SEPERTI INI -->
<td style="max-width: 250px; min-width: 200px; word-break: break-all !important; overflow-wrap: break-word !important; white-space: normal !important;">
    <strong><?= htmlspecialchars($row['judul_survey']); ?></strong><br>
    <small style="color: #858796; word-break: break-all !important; overflow-wrap: break-word !important; white-space: normal !important;">
        <?= htmlspecialchars($row['deskripsi']); ?>
    </small>
</td>
                <td><?= $periode; ?></td>
                <td><?= htmlspecialchars($row['target_prodi']); ?></td> <td><span class="status-badge <?= $status_class; ?>"><?= $status_text; ?></span></td>
                <td>
                    <div class="action-btn-group">
    <button type="button" class="action-btn edit btn-trigger-edit" 
            data-id="<?= $row['id_survey']; ?>"
            data-judul="<?= htmlspecialchars($row['judul_survey']); ?>"
            data-deskripsi="<?= htmlspecialchars($row['deskripsi']); ?>"
            data-mulai="<?= $row['tanggal_mulai']; ?>"
            data-selesai="<?= $row['tanggal_selesai']; ?>"
            data-prodi="<?= htmlspecialchars($row['target_prodi']); ?>"
            data-status="<?= $row['status']; ?>">
        <i class="ri-edit-line"></i> Edit
    </button>
    
    <button type="button" class="action-btn delete btn-trigger-delete-survey" 
            data-id="<?= $row['id_survey']; ?>"
            data-judul="<?= htmlspecialchars($row['judul_survey']); ?>">
        <i class="ri-delete-bin-line"></i> Hapus
    </button>
</div>

                </td>
            </tr>
            <?php
        }
    } else {
        // Tampilan jika database masih kosong atau belum ada kuesioner
        echo "<tr><td colspan='6' style='text-align:center; padding: 30px; color: #777;'>Belum ada survey akademik yang dibuat.</td></tr>";
    }
    ?>
</tbody>
            </table>
        </div>
    </div>

    <!-- Footer Konten Panel -->
    <div class="footer">
        <p>&copy; 2026 Ruang Aspirasi Akademik. All rights reserved. | Control Panel Administrator</p>
    </div>
</main>
  </div>

   <!-- AKHIR AREA KONTEN UTAMA -->

 <script src="https://unpkg.com/@popperjs/core@2"></script>
  <script>
    function _defineProperty(obj, key, value) {
      if (key in obj) {
        Object.defineProperty(obj, key, {
          value: value,
          enumerable: true,
          configurable: true,
          writable: true
        });
      } else {
        obj[key] = value;
      }
      return obj;
    }
    const ANIMATION_DURATION = 300;
    const SIDEBAR_EL = document.getElementById("sidebar");
    const SUB_MENU_ELS = document.querySelectorAll(".menu > ul > .menu-item.sub-menu");
    const FIRST_SUB_MENUS_BTN = document.querySelectorAll(".menu > ul > .menu-item.sub-menu > a");
    const INNER_SUB_MENUS_BTN = document.querySelectorAll(".menu > ul > .menu-item.sub-menu .menu-item.sub-menu > a");

    class PopperObject {
      constructor(reference, popperTarget) {
        _defineProperty(this, "instance", null);
        _defineProperty(this, "reference", null);
        _defineProperty(this, "popperTarget", null);
        this.init(reference, popperTarget);
      }
      init(reference, popperTarget) {
        this.reference = reference;
        this.popperTarget = popperTarget;
        this.instance = Popper.createPopper(this.reference, this.popperTarget, {
          placement: "right",
          strategy: "fixed",
          resize: true,
          modifiers: [{
              name: "computeStyles",
              options: { adaptive: false }
            },
            {
              name: "flip",
              options: { fallbackPlacements: ["left", "right"] }
            }
          ]
        });
        document.addEventListener("click", e => this.clicker(e, this.popperTarget, this.reference), false);
        const ro = new ResizeObserver(() => { this.instance.update(); });
        ro.observe(this.popperTarget);
        ro.observe(this.reference);
      }
      clicker(event, popperTarget, reference) {
        if (SIDEBAR_EL.classList.contains("collapsed") && !popperTarget.contains(event.target) && !reference.contains(event.target)) {
          this.hide();
        }
      }
      hide() { this.instance.state.elements.popper.style.visibility = "hidden"; }
    }

    class Poppers {
      constructor() {
        _defineProperty(this, "subMenuPoppers", []);
        this.init();
      }
      init() {
        SUB_MENU_ELS.forEach(element => {
          this.subMenuPoppers.push(new PopperObject(element, element.lastElementChild));
          this.closePoppers();
        });
      }
      togglePopper(target) {
        if (window.getComputedStyle(target).visibility === "hidden")
          target.style.visibility = "visible";
        else target.style.visibility = "hidden";
      }
      updatePoppers() {
        this.subMenuPoppers.forEach(element => {
          element.instance.state.elements.popper.style.display = "none";
          element.instance.update();
        });
      }
      closePoppers() {
        this.subMenuPoppers.forEach(element => { element.hide(); });
      }
    }

    const slideUp = (target, duration = ANIMATION_DURATION) => {
      const { parentElement } = target;
      parentElement.classList.remove("open");
      target.style.transitionProperty = "height, margin, padding";
      target.style.transitionDuration = `${duration}ms`;
      target.style.boxSizing = "border-box";
      target.style.height = `${target.offsetHeight}px`;
      target.offsetHeight;
      target.style.overflow = "hidden";
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      window.setTimeout(() => {
        target.style.display = "none";
        target.style.removeProperty("height");
        target.style.removeProperty("padding-top");
        target.style.removeProperty("padding-bottom");
        target.style.removeProperty("margin-top");
        target.style.removeProperty("margin-bottom");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
      }, duration);
    };
    
    const slideDown = (target, duration = ANIMATION_DURATION) => {
      const { parentElement } = target;
      parentElement.classList.add("open");
      target.style.removeProperty("display");
      let { display } = window.getComputedStyle(target);
      if (display === "none") display = "block";
      target.style.display = display;
      const height = target.offsetHeight;
      target.style.overflow = "hidden";
      target.style.height = 0;
      target.style.paddingTop = 0;
      target.style.paddingBottom = 0;
      target.style.marginTop = 0;
      target.style.marginBottom = 0;
      target.offsetHeight;
      target.style.boxSizing = "border-box";
      target.style.transitionProperty = "height, margin, padding";
      target.style.transitionDuration = `${duration}ms`;
      target.style.height = `${height}px`;
      target.style.removeProperty("padding-top");
      target.style.removeProperty("padding-bottom");
      target.style.removeProperty("margin-top");
      target.style.removeProperty("margin-bottom");
      window.setTimeout(() => {
        target.style.removeProperty("height");
        target.style.removeProperty("overflow");
        target.style.removeProperty("transition-duration");
        target.style.removeProperty("transition-property");
      }, duration);
    };

    const slideToggle = (target, duration = ANIMATION_DURATION) => {
      if (window.getComputedStyle(target).display === "none")
        return slideDown(target, duration);
      return slideUp(target, duration);
    };

    const PoppersInstance = new Poppers();

    document.getElementById("btn-collapse").addEventListener("click", () => {
      SIDEBAR_EL.classList.toggle("collapsed");
    });

    

    // --- FUNGSI GABUNGAN: LIVE SEARCH, FILTER STATUS, & RE-INDEX NOMOR ---
    function filterSurvey() {
        let searchInput = document.getElementById("surveyInput").value.toLowerCase();
        let statusInput = document.getElementById("statusFilter").value;
        let rows = document.getElementById("surveyTableBody").getElementsByTagName("tr");
        let nomorBaru = 1;

        for (let i = 0; i < rows.length; i++) {
            if (rows[i].getElementsByTagName("td").length < 5) continue;
            let judul = rows[i].getElementsByTagName("td")[1].textContent.toLowerCase();
            let statusBadgeText = rows[i].getElementsByTagName("td")[4].textContent.trim();

            let matchesSearch = judul.includes(searchInput);
            let matchesStatus = (statusInput === "") || (statusBadgeText === statusInput);

            if (matchesSearch && matchesStatus) {
                rows[i].style.display = ""; 
                rows[i].getElementsByTagName("td")[0].textContent = nomorBaru;
                nomorBaru++; 
            } else {
                rows[i].style.display = "none"; 
            }
        }
    }

    

    // --- LOGIKA OPERASIONAL MODAL UTAMA (BERSIH & EVALUASI EVENT KANVAS GLOBAL) ---
    const s_addModal = document.getElementById("addSurveyModal");
    const s_editModal = document.getElementById("editSurveyModal"); // SINKRON: Id Sesuai HTML lu
    const s_deleteModal = document.getElementById("deleteSurveyConfirmationModal");
    const s_deleteTitleTarget = document.getElementById("deleteSurveyTitleText");
    const s_confirmDeleteBtn = document.getElementById("confirmDeleteSurveyActionBtn");

    // Fungsi khusus buka modal tambah (pemicu atribut inline onclick lu)
    function openAddModal() {
        if (s_addModal) s_addModal.style.display = "flex";
    }

    // Satukan Penangan Klik Event agar Terbebas dari Masalah Loading DOM
    document.onclick = function(event) {
        console.log("Klik terdeteksi pada:", event.target); 
        
        // LANGKAH 1: Pindahkan inisialisasi modal ke DALAM sini
        const s_addModal = document.getElementById("addSurveyModal");
        const s_editModal = document.getElementById("editSurveyModal");
        const s_deleteModal = document.getElementById("deleteSurveyConfirmationModal");
        const s_deleteTitleTarget = document.getElementById("deleteSurveyTitleText");
        const s_confirmDeleteBtn = document.getElementById("confirmDeleteSurveyActionBtn");
        
        if (event.target.closest(".btn-add-survey")) {
            event.preventDefault();
            console.log("Tombol Tambah Survey Berhasil Ditekan!");
            if (s_addModal) s_addModal.style.display = "flex";
        }
        // --- JIKA USER KLIK TOMBOL EDIT ---
        if (event.target.closest(".btn-survey-action.edit") || event.target.closest(".action-btn.edit")) {
            event.preventDefault();
            console.log("Tombol Edit Berhasil Ditekan!");
            
            // FIX: Sekarang variabel 'btn' sudah didefinisikan dengan benar
            const btn = event.target.closest(".btn-survey-action.edit") || event.target.closest(".action-btn.edit");
                
            // Ekstrak data-atribut dari tombol kuesioner
            const idSurvey = btn.getAttribute("data-id");
            const judulSurvey = btn.getAttribute("data-judul");
            const deskripsiSurvey = btn.getAttribute("data-deskripsi");
            const tglMulai = btn.getAttribute("data-mulai");
            const tglSelesai = btn.getAttribute("data-selesai");
            const prodiTarget = btn.getAttribute("data-prodi");
            const statusSurvey = btn.getAttribute("data-status");
            
            // Lempar data ke dalam Form Input Modal Edit
            if(document.getElementById("edit_id_survey")) document.getElementById("edit_id_survey").value = idSurvey;
            if(document.getElementById("edit_judul_survey")) document.getElementById("edit_judul_survey").value = judulSurvey;
            if(document.getElementById("edit_deskripsi_survey")) document.getElementById("edit_deskripsi_survey").value = deskripsiSurvey;
            if(document.getElementById("edit_tanggal_mulai")) document.getElementById("edit_tanggal_mulai").value = tglMulai;
            if(document.getElementById("edit_tanggal_selesai")) document.getElementById("edit_tanggal_selesai").value = tglSelesai;
            if(document.getElementById("edit_target_prodi")) document.getElementById("edit_target_prodi").value = prodiTarget;
            if(document.getElementById("edit_status")) document.getElementById("edit_status").value = statusSurvey;
            
            if (s_editModal) s_editModal.style.display = "flex";
        }

        // --- EVENT KLIK: TOMBOL HAPUS ---
        if (event.target.closest(".btn-trigger-delete-survey") || event.target.closest(".action-btn.delete") || event.target.closest(".btn-survey-action.delete")) {
            event.preventDefault();
            const btn = event.target.closest(".btn-trigger-delete-survey") || event.target.closest(".action-btn.delete") || event.target.closest(".btn-survey-action.delete");
            
            const idSurvey = btn.getAttribute("data-id");
            const judulSurvey = btn.getAttribute("data-judul");

            if (s_deleteModal && s_deleteTitleTarget && s_confirmDeleteBtn) {
                s_deleteTitleTarget.textContent = judulSurvey + "?";
                s_confirmDeleteBtn.setAttribute("href", `hapus-survey.php?id=${idSurvey}`);
                s_deleteModal.style.display = "flex";
            }
        }

        // --- EVENT KLIK: TOMBOL PENUTUP (BATAL / SILANG X) ---
        if (event.target.closest("#closeAddModalBtn") || event.target.closest("#cancelAddModalBtn")) {
            if (s_addModal) s_addModal.style.display = "none";
        }
        if (event.target.closest("#closeEditModalBtn") || event.target.closest("#cancelEditModalBtn")) {
            if (s_editModal) s_editModal.style.display = "none";
        }
        if (event.target.closest("#closeDeleteSurveyModalBtn") || event.target.closest("#cancelDeleteSurveyModalBtn")) {
            if (s_deleteModal) s_deleteModal.style.display = "none";
        }

        // --- EVENT KLIK: LUAR AREA KOTAK PUTIH ---
        if (event.target === s_addModal) { s_addModal.style.display = "none"; }
        if (event.target === s_editModal) { s_editModal.style.display = "none"; }
        if (event.target === s_deleteModal) { s_deleteModal.style.display = "none"; }
    };
  </script>

  <style>
.table-container table td {
    /* Mengizinkan teks panjang atau tanpa spasi patah ke bawah otomatis */
    word-break: break-word;
    overflow-wrap: break-word;
    white-space: normal !important;
}

/* Pastikan kolom Judul & Deskripsi punya batasan maksimal atau fleksibilitas yang pas */
.table-container table td:nth-child(2) {
    max-width: 300px; /* Batasi lebar maksimal kolom judul & deskripsi */
    min-width: 200px;
}

    /* GANTI ATAU TAMBAHKAN KODE INI DI DALAM TAG <style> LU */

.action-btn.delete {
    background: rgba(231, 76, 60, 0.1); 
    color: #e74c3c;
    transition: background 0.2s, color 0.2s;
}

/* Ini penangkalnya biar pas kursor nempel, warnanya langsung berubah merah solid */
.action-btn.delete:hover {
    background: #e74c3c !important; 
    color: white !important;
    text-decoration: none !important;
}

    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }
    .modal-content {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        width: 460px;
        max-width: 90%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        position: relative;
        animation: modalFadeIn 0.3s;
    }
    @keyframes modalFadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }
    .modal-header h3 { margin: 0; color: #333; }
    .close-modal { color: #aaa; font-size: 28px; font-weight: bold; cursor: pointer; }
    .close-modal:hover { color: #333; }
    .modal .form-control { margin-bottom: 15px; }
    .modal .form-control label { display: block; margin-bottom: 5px; color: #555; font-size: 0.9em; }
    .modal .form-control input {
        width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px;
        font-size: 0.95rem; background: #f9f9f9; font-family: inherit; outline: none;
    }
    .modal .form-control input:focus { border-color: #4e73df; background: #fff; }
    .modal-footer { margin-top: 25px; display: flex; justify-content: flex-end; gap: 10px; }
    .modal-btn { padding: 10px 20px; border-radius: 6px; border: none; cursor: pointer; font-weight: 500; font-family: inherit; }
    .btn-cancel { background: #eee; color: #333; }
    .btn-save { background: #4e73df; color: white; }
    .btn-save:hover { background: #2e59d9; }
  </style>

  <div id="editSurveyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit & Perpanjang Survey</h3>
            <span class="close-modal" id="closeEditModalBtn">&times;</span>
        </div>
        <form action="edit-survey.php" method="POST">
            <input type="hidden" name="id_survey" id="edit_id_survey">
            <div class="form-control">
                <label>Judul Survey</label>
                <input type="text" name="judul_survey" id="edit_judul_survey" required>
            </div>
            <div class="form-control">
                <label>Deskripsi Singkat</label>
                <input type="text" name="deskripsi" id="edit_deskripsi">
            </div>
  <div class="form-control" style="display: flex; gap: 10px;">
    <div style="flex: 1;">
        <label>Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" id="edit_tanggal_mulai" required>
    </div>
    <div style="flex: 1;">
        <label style="font-size: 11px; white-space: nowrap;">Tanggal Selesai (Perpanjang di sini)</label>
        <input type="date" name="tanggal_selesai" id="edit_tanggal_selesai" required>
    </div>
  </div>
  <div class="form-control">
    <label>Target Responden (Prodi)</label>
    <input type="text" name="target_prodi" id="edit_target_prodi" required placeholder="Contoh: Teknik Informatika / Semua Prodi">
</div>
            <div class="form-control">
    <label>Status</label>
    <div class="form-control">
    <label>Status</label>
    <select name="status" id="edit_status" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; background: #f9f9f9; font-family:inherit; outline: none;">
        <option value="aktif">Aktif</option>
        <option value="draft">Draft (Simpan Sementara)</option>
        <option value="non-aktif">Ditutup (Non-Aktif)</option>
    </select>
</div>
</div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" id="cancelEditModalBtn">Batal</button>
                <button type="submit" class="modal-btn btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
  </div>

  <div id="addSurveyModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Tambah Survey Baru</h3>
            <span class="close-modal" id="closeAddModalBtn">&times;</span>
        </div>
        <form action="tambah-survey.php" method="POST">
            <div class="form-control">
                <label>Judul Survey</label>
                <input type="text" name="judul_survey" required placeholder="Contoh: Evaluasi Dosen Ganjil 2025/2026">
            </div>
            <div class="form-control">
                <label>Deskripsi Singkat</label>
                <input type="text" name="deskripsi" placeholder="Masukkan deskripsi singkat kuesioner...">
            </div>
            <div class="form-control" style="display: flex; gap: 10px;">
                <div style="flex: 1;">
                    <label>Tanggal Mulai</label>
                    <input type="date" name="tanggal_mulai" required>
                </div>
                <div style="flex: 1;">
                    <label>Tanggal Selesai</label>
                    <input type="date" name="tanggal_selesai" required>
                </div>
            </div>
            <div class="form-control">
                <label>Target Responden (Prodi)</label>
                <input type="text" name="target_prodi" required placeholder="Contoh: Teknik Informatika / Semua Prodi">
            </div>
            <div class="form-control">
                <label>Status Awal</label>
                <select name="status" style="width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 6px; background: #f9f9f9; font-family:inherit; outline: none;">
                    <option value="Draft">Draft (Simpan Sementara)</option>
                    <option value="Aktif">Aktif (Langsung Rilis)</option>
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" id="cancelAddModalBtn">Batal</button>
                <button type="submit" class="modal-btn btn-save">Simpan Survey</button>
            </div>
        </form>
    </div>
  </div>

  <!-- ==================== POPUP MODAL KONFIRMASI HAPUS SURVEY (CUSTOM) ==================== -->
<div id="deleteSurveyConfirmationModal" class="modal">
    <div class="modal-content" style="width: 400px; text-align: center; padding: 25px;">
        <div class="modal-header" style="border-bottom: none; padding-bottom: 0; margin-bottom: 10px;">
            <h3 style="margin: 0; font-size: 18px; font-weight: 700; color: #333;">Konfirmasi Hapus</h3>
            <span class="close-modal" id="closeDeleteSurveyModalBtn" style="font-size: 22px;">&times;</span>
        </div>
        
        <div style="margin: 15px 0;">
            <!-- Ikon Tanda Seru Merah Bulat Tengah -->
            <div style="font-size: 50px; color: #e74c3c; line-height: 1; margin-bottom: 15px;">
                <i class="ri-error-warning-line"></i>
            </div>
            <p style="color: #555; font-size: 14px; margin: 0 0 5px 0;">Yakin mau hapus data survey:</p>
            <p id="deleteSurveyTitleText" style="font-weight: 700; color: #333; font-size: 14px; margin: 0; padding: 0 10px;"></p>
        </div>

        <div style="display: flex; justify-content: center; gap: 10px; margin-top: 25px;">
            <button type="button" class="modal-btn btn-cancel" id="cancelDeleteSurveyModalBtn" style="background: #eaecf4; color: #5a5c69; font-weight: 600; padding: 8px 20px; border-radius: 6px;">Batal</button>
            <a href="#" id="confirmDeleteSurveyActionBtn" class="modal-btn" style="background: #e74c3c; color: white; padding: 8px 20px; border-radius: 6px; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; justify-content: center;">Ya, Hapus</a>
        </div>
    </div>
</div>
</body>
</html>