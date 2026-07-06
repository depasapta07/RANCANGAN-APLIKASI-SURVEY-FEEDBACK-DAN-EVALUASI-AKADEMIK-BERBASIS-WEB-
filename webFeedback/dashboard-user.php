<!DOCTYPE html>
<?php
session_start();
include 'db.php'; // << 1. HUBUNGKAN DULU FILE DATABASE LU DI SINI

// 1. Hitung Total Survey yang statusnya aktif (tersedia untuk mahasiswa)
$sql_total = "SELECT COUNT(*) as total FROM surveys";
$res_total = $conn->query($sql_total);
$row_total = $res_total->fetch_assoc();
$total_survey = $row_total['total'];

// 2. Hitung Survey yang Sudah Diisi oleh mahasiswa yang sedang login
// (Pastikan kamu menyimpan id_mahasiswa / NIM di session saat login, contoh: $_SESSION['id_user'])
$id_mahasiswa = isset($_SESSION['id_mahasiswa']) ? $_SESSION['id_mahasiswa'] : 0; 

$sql_diisi = "SELECT COUNT(DISTINCT p.id_survey) as total 
              FROM jawaban j
              JOIN pertanyaan p ON j.id_pertanyaan = p.id_pertanyaan
              WHERE j.id_mahasiswa = '$id_mahasiswa'";

$res_diisi = $conn->query($sql_diisi);
$row_diisi = $res_diisi->fetch_assoc();
$sudah_diisi = $row_diisi['total'] ? $row_diisi['total'] : 0;

// 3. Hitung Survey yang Belum Diisi
$belum_diisi = $total_survey - $sudah_diisi;
if ($belum_diisi < 0) $belum_diisi = 0; // Jaga-jaga agar tidak minus

// 4. Hitung Progres Pengisian (Persentase %)
$progres = 0;
if ($total_survey > 0) {
    $progres = round(($sudah_diisi / $total_survey) * 100);
}

// Jika tidak ada session nama/npm (belum login), tendang kembali ke halaman login
if (!isset($_SESSION['npm'])) {
    header("Location: login.php");
    exit();
}

// 2. QUERY AMBIL SURVEY DARI DATABASE (LIMIT CUMA 2 DATA TERBARU YANG STATUSNYA AKTIF)
$sql_survey_user = "SELECT * FROM surveys WHERE status = 'aktif' ORDER BY id_survey DESC LIMIT 2";
$result_survey_user = $conn->query($sql_survey_user);
?>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.15.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
	<title>Ruang Aspirasi-Dashboard Mahasiswa</title>
	<style>
		@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500&display=swap');
		@import url("https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css");

		:root {
			--primary: #8B5CF6;
	        --secondary: #6D28D9;
        	--accent: #A78BFA;
        	--bg: #F5F3FF;
        	--text: #2E1065;
        	--white: #FFFFFF;
		}

		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}

		body {
        	display: flex;
        	width: 100%;
        	height: 100vh;
	        justify-content: flex-start;
			background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
			background-position: center;
			background-size: cover;
            overflow: hidden;
		}

		.container {
            position: fixed;
        	left: 0;
	        top: 0;
			display: flex;
			flex-direction: column;
			width: 240px;
			height: 100vh;
			border-top-left-radius: 10px;
			border-bottom-left-radius: 10px;
			align-items: center;
			box-shadow: 0px 4px 10px 1px rgba(0, 0, 0, 0.2);
            will-change: width;
			overflow: hidden;
			transition: 0.4s ease;
			background: rgba(139, 92, 246, 0.25);
			box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
			backdrop-filter: blur(30px);
            color: var(--white);
            border: 1px solid rgba(255,255,255,0.2);
			-webkit-backdrop-filter: blur(3px);
			border: 1px solid rgba(255, 255, 255, 0.18);
		}

		.container .brand {
            display: flex;
            width: 100%;
            height: auto; /* Diubah ke auto agar judul 2 baris pas dan tidak sesak */
            padding: 15px 15px; /* Sedikit memperbesar ruang di sekeliling teks */
            margin-bottom: 40px; /* INI KUNCINYA: Memberikan jarak 40px ke menu di bawahnya */
            margin-top: 10px;
            align-items: center;
            justify-content: space-between;
        }
        
        .container .brand h3 {
            font-weight: 700; /* Dibuat lebih tebal (Bold) */
            font-size: 18px; /* Ukuran diperbesar sedikit */
            line-height: 1.3;
            letter-spacing: 0.5px;
            
            /* Efek Teks Gradasi (Gradient) */
            background: linear-gradient(45deg, #4C1D95, #8B5CF6); 
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            
            /* Efek bayangan agar teks terlihat hidup dan tidak menyatu dengan background */
            filter: drop-shadow(0px 2px 3px rgba(76, 29, 149, 0.2));
            
            word-wrap: break-word;
            white-space: normal;
        }

        container .brand h3 span {
            font-size: 13px;
            font-weight: 500;
            
            /* Mengembalikan warna solid untuk sub-judul */
            -webkit-text-fill-color: var(--secondary); 
            opacity: 0.9;
            display: block; /* Memastikan sub-judul turun ke baris baru dengan rapi */
            margin-top: 2px;
        }

		.container .brand a i {
			color: #2E1065FF;  
			font-size: 30px;
		}

		.container .navbar {
			display: flex;
			width: 90%;
			height: auto;
			margin: 10px 0;
			align-items: center;
			justify-content: center;
		}

		.container .navbar ul {
			list-style-type: none;
			display: flex;
			flex-direction: column;
			height: 100%;
			width: 100%;
			align-items: flex-start;
			justify-content: center;
		}

		.container .navbar ul li {
			height: 40px;
			width: 100%;
			margin: 5px 0;
		}

		.container .navbar ul li a {
			display: flex;
            color: #2E1065FF;
			height: 100%;
			text-decoration: none;
			border-radius: 7px;
			align-items: center;
			justify-content: left;
		}

		.add .navbar:nth-child(4) {
			margin: 0;
		}

		.container .navbar ul li a {
			color: #2E1065;
	        transition: 0.3s ease;
		}

		.container .navbar ul li a span {
			font-size: 14px;
	        font-weight: 500;
	        letter-spacing: 0.3px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
		}

		.container .navbar ul li a i {
            color: #2E1065;
            font-weight: 600;
	        transition: 0.3s ease;
			display: flex;
			font-size: 20px;
			margin: 0 15px;
		}

		.container .navbar ul li a:hover,
		.container .navbar ul li a:hover span,
		.container .navbar ul li a:hover i {
			background: #d5cfcf14;
			backdrop-filter: blur(75px);
			/* color: #000; */
			transition: 0.5s all;

		}

		.container .navbar ul li a:hover{
			background: rgba(167, 139, 250, 0.3);
	box-shadow: 0 5px 15px rgba(139, 92, 246, 0.3);
		}

		.container.active {
			width: 80px;
		}

		.container.active .brand {
			justify-content: center;
		}

		.container.active .navbar ul {
			width: 90%;
		}

		.container.active .user {
			width: 80%;
			height: 100%;
		}

		.container.active .navbar ul li a {
			justify-content: center;
		}

		.container.active .brand .logo,
		.container.active .brand h3,
		.container.active .navbar ul li a span,
		.container.active .user .name {
			display: none;
		}

		.log-out{
			position: relative;
			top: 150px;
		}
		
        /* -- External Social Link CSS Styles -- */

        #source-link {
            top: 120px;
        }

        #source-link>i {
            color: rgb(94, 106, 210);
        }

        #yt-link {
            top: 65px;
        }

        #yt-link>i {
            color: rgb(219, 31, 106);

        }

        #Fund-link {
            top: 10px;
        }

        #Fund-link>i {
            color: rgb(255, 251, 0);

        }

        .meta-link {
            align-items: center;
            backdrop-filter: blur(3px);
            background-color: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1);
            cursor: pointer;
            display: inline-flex;
            gap: 5px;
            left: 10px;
            padding: 10px 20px;
            position: fixed;
            text-decoration: none;
            transition: background-color 600ms, border-color 600ms;
            z-index: 10000;
        }

        .meta-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .meta-link>i,
        .meta-link>span {
            height: 20px;
            line-height: 20px;
        }

        .meta-link>span {
            color: white;
            font-family: "Rubik", sans-serif;
            transition: color 600ms;
        }
    /* ISI DASHBOARD */
    
        .main-content {
    will-change: margin-left, width;
    margin-left: 240px;
    padding: 20px;
    width: calc(100% - 240px);
    height: 100vh; /* KUNCI 2: Memaksa area konten mengikuti tinggi layar */
    color: var(--text);
    transition: margin-left 0.4s ease, width 0.4s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between; /* Membuat konten terbagi rata dari atas ke bawah */
    overflow: hidden; /* Mengunci konten di dalam */
}

.container.active ~ .main-content {
	margin-left: 80px;
    width: calc(100% - 80px);
}

/* TITLE */
.main-content h1 {
	margin-bottom: 20px;
	font-weight: 600;
}

/* CARDS */
.cards {
	display: grid;
	grid-template-columns: repeat(4, 1fr);
	gap: 20px;
	margin-bottom: 30px;
}

.card {
	background: rgba(255,255,255,0.7);
	backdrop-filter: blur(10px);
	border-radius: 15px;
	padding: 20px;
	box-shadow: 0 5px 15px rgba(0,0,0,0.1);
	transition: 0.3s;
}

.card:hover {
	transform: translateY(-5px);
	box-shadow: 0 10px 25px rgba(139, 92, 246, 0.3);
}

.card h3 {
	font-size: 14px;
	color: #6B7280;
	margin-bottom: 10px;
}

.card p {
	font-size: 22px;
	font-weight: 600;
	color: var(--primary);
}

/* TABLE */
.table-section {
	background: rgba(255,255,255,0.7);
	padding: 20px;
	border-radius: 15px;
	backdrop-filter: blur(10px);
	box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.table-section h2 {
	margin-bottom: 15px;
}

table {
	width: 100%;
	border-collapse: collapse;
}

table th, table td {
	padding: 10px;
	text-align: left;
}

table th {
	color: #6B7280;
	font-weight: 500;
}

table tr {
	border-bottom: 1px solid #ddd;
}

/* BADGE */
.badge {
	padding: 5px 10px;
	border-radius: 10px;
	font-size: 12px;
	color: white;
}

.badge.aktif {
	background: #8B5CF6;
}

.badge.selesai {
	background: #10B981;
}

/* --- INFO MAHASISWA (TAMBAHAN BARU) --- */
.student-info-section {
    background: var(--white);
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    margin-bottom: 25px;
    border: 1px solid #E5E7EB;
}

.student-info-section h2 {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 20px;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr); /* Membagi rata menjadi 3 kolom */
    gap: 20px;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 15px;
}

.info-icon-box {
    background: #EEF2FF; /* Warna background ikon (biru muda) */
    color: #6366F1;      /* Warna ikon (ungu/biru) */
    width: 48px;
    height: 48px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

.info-text label {
    display: block;
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 2px;
}

.info-text span {
    font-size: 15px;
    font-weight: 600;
    color: #111827;
}

/* LAYOUT KIRI KANAN */
.content-row {
	display: grid;
	grid-template-columns: 2fr 1fr;
	gap: 20px;
	margin-top: 20px;
}

/* --- CSS BARU UNTUK DAFTAR & RIWAYAT SURVEY --- */

.survey-list-section, .history-section {
    background: #FFFFFF;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    border: 1px solid #E5E7EB;
}

.survey-list-section h2, .history-section h2 {
    font-size: 16px;
    font-weight: 600;
    color: #1F2937;
    margin-bottom: 20px;
}

/* Item Daftar Survey */
.survey-card-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border: 1px solid #E5E7EB;
    border-radius: 10px;
    margin-bottom: 15px;
    background: #FAFAFA;
    gap: 20px;
}

.survey-icon-box {
    background: #EEF2FF;
    color: #4F46E5; 
    width: 50px;
    height: 50px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 24px;
    flex-shrink: 0;
}

.survey-info {
    flex-grow: 1;
}

.survey-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 6px;
}

.survey-info p {
    font-size: 12px;
    color: #6B7280;
    margin-bottom: 10px;
    line-height: 1.5;
}

.survey-date {
    font-size: 12px;
    color: #6B7280;
    display: flex;
    align-items: center;
    gap: 5px;
}

.survey-actions {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 12px;
    min-width: 110px;
}

/* Badges */
.badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 500;
    text-align: center;
}

.badge-warning {
    background: #FFFBEB;
    color: #D97706;
}

.badge-success {
    background: #ECFDF5;
    color: #059669;
}

/* Buttons */
.btn-primary {
    background: #3B82F6;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}

.btn-primary:hover {
    background: #2563EB;
}

.btn-outline {
    background: transparent;
    color: #3B82F6;
    border: 1px solid #E5E7EB;
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    width: 100%;
    transition: 0.3s;
}

.btn-outline:hover {
    background: #F3F4F6;
}

.btn-block-left {
    width: auto;
    margin-top: 10px;
}

.mt-3 {
    margin-top: 15px;
}

/* Item Riwayat Survey */
.history-list {
    display: flex;
    flex-direction: column;
}

.history-item {
    display: flex;
    align-items: center;
    padding: 15px 0;
    border-bottom: 1px solid #E5E7EB;
    gap: 15px;
}

.history-item:last-child {
    border-bottom: none;
}

.history-icon {
    color: #10B981; 
    background: #ECFDF5;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 16px;
    flex-shrink: 0;
}

.history-info {
    flex-grow: 1;
}

.history-info h4 {
    font-size: 13px;
    font-weight: 600;
    color: #111827;
    margin-bottom: 4px;
}

.history-info p {
    font-size: 11px;
    color: #6B7280;
}

.history-link {
    font-size: 12px;
    color: #3B82F6;
    text-decoration: none;
    font-weight: 500;
}

.history-link:hover {
    text-decoration: underline;
}

/* --- FOOTER DASHBOARD --- */
.footer {
    margin-top: auto;
    padding-top: 20px;
    padding-bottom: 10px;
    border-top: 1px solid #E5E7EB;
    text-align: center;
    color: #6B7280; /* Warna abu-abu lembut agar tidak mencolok */
    font-size: 13px;
    width: 100%;
}

.footer p {
    font-weight: 400;
}

	</style>
		<script>

		</script>

</head>
<body >
	<div class="container add" id="container" >
		<div class="brand">
			<h3>
                Ruang Aspirasi  <br>
                 <span>Akademik</span>
            </h3>
			<a href="#" id="toggle"><i class="bi bi-list"></i></a>
		</div>

		<div class="navbar">
			<ul>

				<li>
					<a href="#"><i class="bi bi-house"></i><span>DashBoard</span></a></li>
				<li><a href="daftar-survey-user.php"><i class="bi bi-journal-text"></i><span>Daftar Survey</span></a></li>
				<li><a href="riwayat-survey-user.php"><i class="bi bi-clock-history"></i><span>Riwayat Survey</span></a></li>
		</div>


		<div class="navbar log-out">
			<ul>
				<li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i><span>Log Out</span></a></li>
			</ul>
		</div>
	</div>
                <!-- ISI DI SINII         -->
                <div class="main-content">
	<h1>Dashboard Mahasiswa</h1>

	<div class="cards">
    <!-- Card 1: Total Survey (Link ke daftar survey) -->
    <a href="daftar-survey-user.php" class="card-link" style="text-decoration: none; color: inherit; display: block;">
        <div class="card">
            <h3>Total Survey</h3>
            <p><?= $total_survey; ?></p>
        </div>
    </a>

    <!-- Card 2: Sudah Diisi (Link ke riwayat survey) -->
    <a href="riwayat-survey.php" class="card-link" style="text-decoration: none; color: inherit; display: block;">
        <div class="card">
            <h3>Sudah Diisi</h3>
            <p><?= $sudah_diisi; ?></p>
        </div>
    </a>

    <!-- Card 3: Belum Diisi (Link ke daftar survey) -->
    <a href="daftar-survey-user.php" class="card-link" style="text-decoration: none; color: inherit; display: block;">
        <div class="card">
            <h3>Belum Diisi</h3>
            <p><?= $belum_diisi; ?></p>
        </div>
    </a>

    <!-- Card 4: Progres Pengisian (Tanpa link / tetap) -->
    <div class="card">
        <h3>Progres Pengisian</h3>
        <p><?= $progres; ?>%</p>
    </div>
</div>

<div class="student-info-section">
    <h2 class="section-title">Informasi Mahasiswa</h2>
    <div class="info-grid">
        <div class="info-item">
            <div class="info-icon-box">
                <i class="bi bi-person"></i>
            </div>
            <div class="info-text">
                <label>Nama</label>
                <span><?php echo htmlspecialchars($_SESSION['nama']); ?></span>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon-box">
                <i class="bi bi-card-text"></i>
            </div>
            <div class="info-text">
                <label>NPM</label>
                <span><?php echo htmlspecialchars($_SESSION['npm']); ?></span>
            </div>
        </div>
        <div class="info-item">
            <div class="info-icon-box">
                <i class="bi bi-mortarboard"></i>
            </div>
            <div class="info-text">
                <label>Program Studi</label>
                <span><?php echo htmlspecialchars($_SESSION['prodi']); ?></span>
            </div>
        </div>
    </div>
</div>

	<div class="content-row">
    <!-- KIRI: DAFTAR SURVEY TERSEDIA -->
    <div class="survey-list-section">
        <h2>Daftar Survey Tersedia</h2>

        <?php
        // Cek apakah ada survey aktif di database
        if ($result_survey_user && $result_survey_user->num_rows > 0) {
            while ($row = $result_survey_user->fetch_assoc()) {
                // Format rentang tanggal rilis biar rapi
                $periode_user = date('d M Y', strtotime($row['tanggal_mulai'])) . " - " . date('d M Y', strtotime($row['tanggal_selesai']));
                ?>
                <div class="survey-card-item">
    <div class="survey-icon-box">
        <i class="bi bi-clipboard-check"></i>
    </div>
    <div class="survey-info">
        <!-- 1. KOMPONEN JUDUL KUESIONER -->
        <h4><?= htmlspecialchars($row['judul_survey']); ?></h4>
        
        <!-- 2. KOMPONEN DESKRIPSI KUESIONER -->
        <p style="margin-bottom: 8px; color: #6B7280; font-size: 13px;">
    <?= !empty($row['deskripsi']) ? htmlspecialchars($row['deskripsi']) : '--- Deskripsi survey ini kosong ---'; ?>
</p>
        
        <!-- 3. KOMPONEN PERIODE RILIS -->
        <span class="survey-date" style="margin-bottom: 4px;">
            <i class="bi bi-calendar4"></i> 
            <strong>Periode:</strong> <?= date('d M Y', strtotime($row['tanggal_mulai'])) . " - " . date('d M Y', strtotime($row['tanggal_selesai'])); ?>
        </span>
        
        <!-- 4. KOMPONEN TARGET RESPONDEN -->
        <span class="survey-target" style="font-size: 11px; color: #6D28D9; display: flex; align-items: center; gap: 5px;">
            <i class="bi bi-mortarboard"></i> 
            <strong>Target Responden:</strong> <?= htmlspecialchars($row['target_prodi']); ?>
        </span>
    </div>
    <div class="survey-actions">
        <span class="badge badge-warning">Belum Diisi</span>
        <a href="isi-survey.php?id=<?= $row['id_survey']; ?>" class="btn-primary" style="text-align: center; text-decoration: none;">Isi Survey</a>
    </div>
</div>
                <?php
            }
        } else {
            // Tampilan jika admin belum merilis kuesioner aktif apapun
            echo "<p style='color: #777; font-size: 13px; padding: 15px 0;'>Saat ini tidak ada survey akademik yang tersedia untuk diisi.</p>";
        }
        ?>

        <a href="daftar-survey-user.php" class="btn-outline btn-block-left" style="text-decoration: none; display: inline-block; text-align: center;">Lihat Semua Survey</a>
    </div>

    <!-- KANAN: RIWAYAT SURVEY TERAKHIR -->
    <div class="history-section">
        <h2>Riwayat Survey Terakhir</h2>

        <div class="history-list">
            <div class="history-item">
                <div class="history-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="history-info">
                    <h4>Evaluasi Fasilitas Kampus</h4>
                    <p>Diisi pada: 18 April 2024 10:30</p>
                </div>
                <a href="#" class="history-link">Lihat Detail</a>
            </div>

            <div class="history-item">
                <div class="history-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="history-info">
                    <h4>Evaluasi Dosen Ganjil 2023/2024</h4>
                    <p>Diisi pada: 20 Desember 2023 14:15</p>
                </div>
                <a href="#" class="history-link">Lihat Detail</a>
            </div>
            
            <div class="history-item">
                <div class="history-icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <div class="history-info">
                    <h4>Survey Layanan Perpustakaan</h4>
                    <p>Diisi pada: 05 Desember 2023 09:20</p>
                </div>
                <a href="#" class="history-link">Lihat Detail</a>
            </div>
        </div>
        <button class="btn-outline btn-block-left mt-3">Lihat Semua Riwayat</button>
    </div>
</div>
    <div class="footer">
            <p>&copy; 2026 Ruang Aspirasi Akademik. All rights reserved. | Dibuat untuk Evaluasi Mahasiswa</p>
        </div>
</div>

	<script>
		var toggle = document.getElementById("toggle");
		var container = document.getElementById("container");

		toggle.onclick = function () {
			container.classList.toggle('active');
		}

	</script>
</body>
</html>