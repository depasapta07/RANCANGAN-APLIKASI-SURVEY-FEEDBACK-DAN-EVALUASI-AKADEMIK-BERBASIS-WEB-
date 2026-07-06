<?php
session_start();
include 'db.php'; // File ini wajib ada buat koneksi ke database

// 1. Hitung Total Mahasiswa Terdaftar
$sql_mhs = "SELECT COUNT(*) as total FROM mahasiswa";
$res_mhs = $conn->query($sql_mhs);
$row_mhs = $res_mhs->fetch_assoc();
$total_mahasiswa = $row_mhs['total'] ? $row_mhs['total'] : 0;

// 2. Hitung Respon Masuk (Total mahasiswa unik yang sudah berpartisipasi mengisi survey)
$sql_respon = "SELECT COUNT(DISTINCT id_mahasiswa) as total FROM jawaban";
$res_respon = $conn->query($sql_respon);
$row_respon = $res_respon->fetch_assoc();
$total_respon = $row_respon['total'] ? $row_respon['total'] : 0;

// 3. Hitung Total Seluruh Survey di Tabel Admin (Nama tabel dibetulkan menjadi 'survey')
$sql_survey_admin = "SELECT COUNT(*) as total FROM surveys";
$res_survey_admin = $conn->query($sql_survey_admin);
$row_survey_admin = $res_survey_admin->fetch_assoc();
$total_survey_admin = $row_survey_admin['total'] ? $row_survey_admin['total'] : 0;

// 4. Hitung total mahasiswa
$sql_mhs = "SELECT COUNT(*) as total FROM mahasiswa";
$res_mhs = $conn->query($sql_mhs);
$total_mahasiswa = ($res_mhs) ? $res_mhs->fetch_assoc()['total'] : 0;
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
                <a href="#"style="color: #4e73df; font-weight: bold;">
                  <span class="menu-icon" style="color: #4e73df;">
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
    <div id="overlay" class="overlay"></div>
    <div class="layout">
      <main class="content main">
        
        <div>
          <a id="btn-toggle" href="#" class="sidebar-toggler break-point-sm">
            <i class="ri-menu-line ri-xl" style="color: black;"></i>
          </a>
        </div>

        

        <!-- BAGIAN 1: KARTU STATISTIK (Sesuai Gambar) -->
        <!-- BAGIAN 1: KARTU STATISTIK -->
<div class="cards" style="margin-top: 20px;">
  
  <!-- Card 1: Total Mahasiswa -->
  <div class="card blue">
    <div>
      <h3>Total Mahasiswa</h3> 
      <h1 style="color: #4e73df;"><?php echo $total_mahasiswa; ?></h1>
    </div>
    <i class="ri-group-line ri-3x" style="color: #4e73df;"></i>
  </div>

  <!-- Card 2: Respon masuk jawaban -->
  <div class="card green">
    <div>
      <h3>Respon masuk jawaban</h3>
      <h1 style="color: #1cc88a;"><?php echo $total_respon; ?></h1>
    </div>
    <i class="ri-ping-pong-fill ri-3x" style="color: #1cc88a;"></i>
  </div>

  <!-- Card 3: Total Survey -->
  <div class="card orange">
    <div>
      <h3>Total Survey</h3>
      <h1 style="color: #f6c23e;"><?php echo $total_survey_admin; ?></h1>
    </div>
    <i class="ri-calendar-event-fill ri-3x" style="color: #f6c23e;"></i>
  </div>

</div>

        <!-- BAGIAN 2: KOTAK SELAMAT DATANG (Sesuai Gambar) -->
        <div class="welcome">
          <h2 style="margin-bottom: 15px; color: #333; display: flex; align-items: center; gap: 10px;">
          <span>👋</span> Selamat Datang, Di Halaman Admin!
          </h2>
          <p style="color: #555; margin-bottom: 15px;">Website Survey & Feedback Evaluasi Akademik ini memudahkan Anda untuk:</p>
          
          <ul style="list-style-type: none; padding-left: 0;">
            <li style="margin-bottom: 10px; color: #555;">
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Mengelola data Mahasiswa
            </li>
            <li style="margin-bottom: 10px; color: #555;">
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Mengelola survey
            <li style="margin-bottom: 10px; color: #555;">
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Mengelola pertanyaan
            </li>
            <li style="margin-bottom: 10px; color: #555;">
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Mengelola pertanyaan
            </li>
            <li style="margin-bottom: 10px; color: #555;"></li>
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Membuat pertanyaan 
            </li>
            <li style="margin-bottom: 10px; color: #555;"></li>
              <span class="check" style="color: #1cc88a; margin-right: 8px;">✓</span> Mengelola jawaban
            </li>
          </ul>
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
</body>
</html>