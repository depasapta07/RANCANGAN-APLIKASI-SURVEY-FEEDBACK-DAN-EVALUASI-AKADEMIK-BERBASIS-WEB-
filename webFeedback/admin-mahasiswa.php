<?php
session_start();
include 'db.php';

// Cek apakah admin sudah login. Jika belum, kembalikan ke halaman login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil semua data dari tabel mahasiswa
$search = isset($_GET['search']) ? $_GET['search'] : '';

if (!empty($search)) {
    // Kalau admin ngetik di kolom search, jalanin query ini
    $sql = "SELECT * FROM mahasiswa WHERE nama_mhs LIKE ? OR npm LIKE ? ORDER BY id_mahasiswa DESC";
    $stmt_search = $conn->prepare($sql);
    $search_param = "%$search%";
    $stmt_search->bind_param("ss", $search_param, $search_param);
    $stmt_search->execute();
    $result = $stmt_search->get_result();
} else {
    // Kalau nggak ada pencarian, tampilin semua data kayak biasa
    $sql = "SELECT * FROM mahasiswa ORDER BY id_mahasiswa DESC";
    $result = $conn->query($sql);
}
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
  border-radius: 12px; 
  box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
  overflow: auto; 
  max-height: calc(100vh - 180px); /* Card penuh ke bawah layar */
  position: relative;
  padding: 0; /* WAJIB 0: Biar data nggak nyelip ke atas header */
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

/* --- CSS BARU UNTUK TABEL MAHASISWA --- */
    .page-title { margin-top: 10px; margin-bottom: 15px; color: #333; }
    
    /* Container Utama */
.table-container { 
    background: white; 
    border-radius: 12px; 
    box-shadow: 0 4px 10px rgba(0,0,0,0.05); 
    overflow: auto; 
    max-height: calc(100vh - 180px); 
    position: relative;
    padding: 0 !important; /* WAJIB 0 agar data tidak nembus di atas header */
}

/* Wrapper Search di dalam Card */
.search-wrapper-card {
    position: sticky;
    top: 0;
    left: 0;
    right: 0;
    background: white; /* Harus putih agar menutupi data saat di-scroll */
    z-index: 110; /* Lebih tinggi dari header tabel */
    padding: 20px 25px 10px;
    display: flex;
    justify-content: flex-start;
}

/* Box Input Search */
.search-box {
    position: relative;
    width: 300px;
}
.search-box input {
    width: 100%;
    padding: 10px 40px 10px 15px;
    border-radius: 8px;
    border: 1px solid #ddd;
    outline: none;
    background-color: #f9f9f9;
}

/* Header Tabel (Sticky di bawah Search bar) */
.table-mahasiswa th { 
    background-color: #f8f9fc !important; 
    color: #4e73df; 
    font-weight: 600; 
    position: sticky; 
    top: 65px; /* JARAK INI HARUS PAS (Tinggi search-wrapper-card) */
    z-index: 100; 
    padding: 18px 25px;
    box-shadow: inset 0 -1px 0 #eee; 
}
    
    .table-mahasiswa { 
      width: 100%; 
      border-collapse: separate; /* Gunakan separate agar sticky border jalan */
      border-spacing: 0; 
    }
    
    .table-mahasiswa th, .table-mahasiswa td { 
      padding: 18px 25px; /* Samakan dengan padding header (th) */
      border-bottom: 1px solid #eee;
      text-align: left; 
    }
    
    .table-mahasiswa tr:hover { background-color: #fcfcfc; }
    
    .action-btn { padding: 6px 12px; border: none; border-radius: 6px; cursor: pointer; font-size: 0.85rem; font-weight: 500; display: inline-flex; align-items: center; gap: 5px; transition: all 0.2s ease; }
    .edit { background: rgba(78, 115, 223, 0.1); color: #4e73df; }
    .edit:hover { background: #4e73df; color: white; }
    .delete { background: rgba(231, 76, 60, 0.1); color: #e74c3c; }
    .delete:hover { background: #e74c3c; color: white; }

    /* --- CSS UNTUK MODAL (POP-UP) EDIT --- */
    .modal {
      display: none; /* Sembunyikan modal secara default */
      position: fixed;
      z-index: 9999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5); /* Background gelap transparan */
      justify-content: center;
      align-items: center;
    }

    .modal-content {
      background-color: #fff;
      padding: 30px;
      border-radius: 12px;
      width: 400px;
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

    .modal-header h3 {
      margin: 0;
      color: #333;
    }

    .close-modal {
      color: #aaa;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
      transition: color 0.2s;
    }

    .close-modal:hover, .close-modal:focus {
      color: #333;
    }

    /* Style form di dalam modal (menyesuaikan dengan form-control yang sudah ada) */
    .modal .form-control {
      margin-bottom: 15px;
      position: relative;
    }
    
    .modal .form-control label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-size: 0.9em;
        position: static; /* Timpa absolute dari bawaan form */
        transform: none;
    }

    .modal .form-control input {
      width: 100%;
      padding: 10px 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1rem;
      background: #f9f9f9;
      height: auto; /* Timpa height 100% dari bawaan form */
    }
    
    .modal .form-control input:focus {
        background: #fff;
        border-color: #4e73df;
    }

    .modal-footer {
      margin-top: 25px;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
    }

    .modal-btn {
      padding: 10px 20px;
      border-radius: 6px;
      border: none;
      cursor: pointer;
      font-weight: 500;
      transition: background 0.2s;
    }

    .btn-cancel {
      background: #eee;
      color: #333;
    }
    .btn-cancel:hover { background: #ddd; }

    .btn-save {
      background: #4e73df;
      color: white;
    }
    .btn-save:hover { background: #2e59d9; }

    .search-container {
        display: flex;
        justify-content: flex-end;
        margin-bottom: 15px;
    }
    .search-box {
        position: relative;
        width: 300px;
    }
    .search-box input {
        width: 100%;
        padding: 10px 40px 10px 15px;
        border-radius: 8px;
        border: 1px solid #ddd;
        outline: none;
        font-family: "Poppins", sans-serif;
        transition: 0.3s;
    }
    .search-box input:focus {
        border-color: #4e73df;
        box-shadow: 0 0 8px rgba(78, 115, 223, 0.2);
    }
    .search-box i {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #aaa;
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
                <a href="admin-mahasiswa.php" style="color: #4e73df; font-weight: bold;">
                  <span class="menu-icon" style="color: #4e73df;">
                    <i class="ri-group-line"></i>
                  </span>
                  <span class="menu-title">Data Mahasiswa</span>
                </a>
              </li>

              <li class="menu-item">
                <a href="admin-survey.php">
                  <span class="menu-icon">
                    <i class="ri-file-list-3-line"></i>
                  </span>
                  <span class="menu-title">Kelola Survey</span>
                </a>
              </li>
              <li class="menu-item">
                <a href="admin/admin-tambah-movies.php">
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
                  <span class="menu-title">Create Pertanyaan</span>
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
   <!-- INI ADALAH AREA KONTEN UTAMA -->
    <div id="overlay" class="overlay"></div>
    <main class="content main">
        
        <!-- Tombol Buka/Tutup Sidebar di Mobile -->
        <div>
            <a id="btn-toggle" href="#" class="sidebar-toggler break-point-sm">
                <i class="ri-menu-line ri-xl" style="color: black;"></i>
            </a>
        </div>

        <!-- Judul Halaman -->
        <div class="page-title">
            <h2>Data Akun Mahasiswa</h2>
            
        </div>

        <!-- Tabel Data -->
        <div class="table-container">
          <div class="search-wrapper-card">
    <div class="search-box">
        <!-- Tambah id="mhsInput" dan fungsi onkeyup -->
        <input type="text" id="mhsInput" onkeyup="liveSearch()" placeholder="Cari Nama atau NPM...">
        <i class="ri-search-line"></i>
    </div>
</div>
            <table class="table-mahasiswa">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>NPM</th>
                        <th>Program Studi</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="mhsTable">
                    <?php
    // Cek apakah ada data hasil query
    if ($result->num_rows > 0) {
        $no = 1;
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $no++ . "</td>";
            echo "<td>" . $row['nama_mhs'] . "</td>";
            echo "<td>" . $row['npm'] . "</td>";
            echo "<td>" . $row['prodi'] . "</td>";
            echo "<td>" . $row['password'] . "</td>";
            echo "<td>
        <button type='button' class='action-btn edit' onclick='openEditModal(" . $row['id_mahasiswa'] . ", \"" . addslashes($row['nama_mhs']) . "\", \"" . addslashes($row['npm']) . "\", \"" . addslashes($row['prodi']) . "\", \"" . addslashes($row['password']) . "\")'><i class='ri-edit-line'></i> Edit</button>
        <button type='button' class='action-btn delete' onclick='bukaHapus(" . $row['id_mahasiswa'] . ", \"" . addslashes($row['nama_mhs']) . "\")'><i class='ri-delete-bin-line'></i> Hapus</button>
      </td>";
            echo "</tr>";
        }
    } 
    else {
        // Cek apakah variabel $search ada isinya (lagi nyari sesuatu) atau emang tabelnya kosong
        $pesan = !empty($search) 
                 ? "Hasil pencarian untuk '<b>" . htmlspecialchars($search) . "</b>' tidak ditemukan." 
                 : "Belum ada data mahasiswa terdaftar.";
                 
        echo "<tr><td colspan='5' style='text-align:center; padding: 40px; color: #777;'>$pesan</td></tr>";
    }
    ?>
                </tbody>
            </table>
        </div>
    </main>
        </div>
    <!-- AKHIR AREA KONTEN UTAMA -->
    
  </div>
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
              options: {
                adaptive: false
              }
            },
            {
              name: "flip",
              options: {
                fallbackPlacements: ["left", "right"]
              }
            }
          ]
        });

        document.addEventListener("click", e => this.clicker(e, this.popperTarget, this.reference), false);

        const ro = new ResizeObserver(() => {
          this.instance.update();
        });

        ro.observe(this.popperTarget);
        ro.observe(this.reference);
      }

      clicker(event, popperTarget, reference) {
        if (SIDEBAR_EL.classList.contains("collapsed") && !popperTarget.contains(event.target) && !reference.contains(event.target)) {
          this.hide();
        }
      }

      hide() {
        this.instance.state.elements.popper.style.visibility = "hidden";
      }
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
        this.subMenuPoppers.forEach(element => {
          element.hide();
        });
      }
    }

    const slideUp = (target, duration = ANIMATION_DURATION) => {
      const {
        parentElement
      } = target;
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
      const {
        parentElement
      } = target;
      parentElement.classList.add("open");
      target.style.removeProperty("display");
      let {
        display
      } = window.getComputedStyle(target);
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

    /**
     * wait for the current animation to finish and update poppers position
     */
    const updatePoppersTimeout = () => {
      setTimeout(() => {
        PoppersInstance.updatePoppers();
      }, ANIMATION_DURATION);
    };

    /**
     * sidebar collapse handler
     */
    document.getElementById("btn-collapse").addEventListener("click", () => {
      SIDEBAR_EL.classList.toggle("collapsed");
      PoppersInstance.closePoppers();
      if (SIDEBAR_EL.classList.contains("collapsed"))
        FIRST_SUB_MENUS_BTN.forEach(element => {
          element.parentElement.classList.remove("open");
        });

      updatePoppersTimeout();
    });

    /**
     * sidebar toggle handler (on break point )
     */
    document.getElementById("btn-toggle").addEventListener("click", () => {
      SIDEBAR_EL.classList.toggle("toggled");

      updatePoppersTimeout();
    });

    /**
     * toggle sidebar on overlay click
     */
    document.getElementById("overlay").addEventListener("click", () => {
      SIDEBAR_EL.classList.toggle("toggled");
    });

    const defaultOpenMenus = document.querySelectorAll(".menu-item.sub-menu.open");

    defaultOpenMenus.forEach(element => {
      element.lastElementChild.style.display = "block";
    });

    /**
     * handle top level submenu click
     */
    FIRST_SUB_MENUS_BTN.forEach(element => {
      element.addEventListener("click", () => {
        if (SIDEBAR_EL.classList.contains("collapsed"))
          PoppersInstance.togglePopper(element.nextElementSibling);
        else {
          const parentMenu = element.closest(".menu.open-current-submenu");
          if (parentMenu)
            parentMenu.querySelectorAll(":scope > ul > .menu-item.sub-menu > a").forEach((el) =>
              window.getComputedStyle(el.nextElementSibling).display !== "none" && slideUp(el.nextElementSibling)
            );

          slideToggle(element.nextElementSibling);
        }
      });
    });

    /**
     * handle inner submenu click
     */
    INNER_SUB_MENUS_BTN.forEach(element => {
      element.addEventListener("click", () => {
        slideToggle(element.nextElementSibling);
      });
    });
  </script>
  <!-- === MODAL EDIT (Jangan diubah isinya, cuma pastiin penutupnya bener) === -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Edit Data Mahasiswa</h3>
            <span class="close-modal" id="closeModalBtn">&times;</span>
        </div>
        <form id="editForm" action="edit-mahasiswa.php" method="POST">
            <input type="hidden" id="edit_id" name="id_mahasiswa">
            <div class="form-control">
                <label>Nama Lengkap</label>
                <input type="text" id="edit_nama" name="nama_mhs" required>
            </div>
            <div class="form-control">
                <label>NPM</label>
                <input type="text" id="edit_npm" name="npm" required>
            </div>
            <div class="form-control">
                <label>Program Studi</label>
                <input type="text" id="edit_prodi" name="prodi" required>
            </div>
            <div class="form-control">
                <label>Password</label>
                <input type="text" id="edit_password" name="password" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="modal-btn btn-cancel" id="cancelModalBtn">Batal</button>
                <button type="submit" class="modal-btn btn-save">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- === MODAL HAPUS (Pindahkan ke Sini, di LUAR editModal) === -->
<div id="hapusModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Konfirmasi Hapus</h3>
            <span class="close-modal" onclick="tutupHapus()">&times;</span>
        </div>
        <div style="padding: 20px 0; text-align: center;">
            <i class="ri-error-warning-line" style="font-size: 50px; color: #e74c3c;"></i>
            <p style="margin-top: 10px;">Yakin mau hapus data mahasiswa:<br><b id="namaHapus" style="color: #333;"></b>?</p>
        </div>
        <div class="modal-footer">
            <button type="button" class="modal-btn btn-cancel" onclick="tutupHapus()">Batal</button>
            <a id="linkHapus" href="#" class="modal-btn" style="background: #e74c3c; color: white; text-decoration: none; display: flex; align-items: center; justify-content: center; padding: 10px 20px; border-radius: 6px;">Ya, Hapus</a>
        </div>
    </div>
</div>
  <!-- SCRIPT UNTUK MENGONTROL POP-UP MODAL EDIT -->
  <script>
    const modal = document.getElementById("editModal");
    const closeBtn = document.getElementById("closeModalBtn");
    const cancelBtn = document.getElementById("cancelModalBtn");

    // Fungsi untuk membuka modal dan mengisi datanya otomatis
    function openEditModal(id, nama, npm, prodi, password) { // Tambah parameter password
    document.getElementById('edit_id').value = id;
    document.getElementById('edit_nama').value = nama;
    document.getElementById('edit_npm').value = npm;
    document.getElementById('edit_prodi').value = prodi;
    document.getElementById('edit_password').value = password; // Masukkan ke input password
    
    modal.style.display = "flex"; 
}

    // Fungsi untuk menutup modal
    function closeModal() {
      modal.style.display = "none";
    }

    // Jika tombol (x) atau Batal diklik
    closeBtn.addEventListener("click", closeModal);
    cancelBtn.addEventListener("click", closeModal);

    // Jika area kosong di luar kotak pop-up diklik
    window.addEventListener("click", function(event) {
      if (event.target == modal) {
        closeModal();
      }
    });

    function bukaHapus(id, nama) {
    document.getElementById('namaHapus').innerText = nama;
    document.getElementById('linkHapus').href = 'hapus-mahasiswa.php?id=' + id;
    document.getElementById('hapusModal').style.display = "flex";
}

function tutupHapus() {
    document.getElementById('hapusModal').style.display = "none";
}
  </script>
  <script>
function liveSearch() {
    // 1. Ambil apa yang diketik user
    let input = document.getElementById("mhsInput").value.toLowerCase();
    // 2. Ambil semua baris di dalam tabel
    let rows = document.getElementById("mhsTable").getElementsByTagName("tr");

    for (let i = 0; i < rows.length; i++) {
        // Kita cari di kolom Nama (index 1) dan NPM (index 2)
        let nama = rows[i].getElementsByTagName("td")[1].textContent.toLowerCase();
        let npm = rows[i].getElementsByTagName("td")[2].textContent.toLowerCase();

        // 3. Cek apakah ketikan user ada di Nama atau NPM
        if (nama.includes(input) || npm.includes(input)) {
            rows[i].style.display = ""; // Tampilkan jika cocok
        } else {
            rows[i].style.display = "none"; // Sembunyikan jika tidak cocok
        }
    }
}
</script>
</body>

</html>