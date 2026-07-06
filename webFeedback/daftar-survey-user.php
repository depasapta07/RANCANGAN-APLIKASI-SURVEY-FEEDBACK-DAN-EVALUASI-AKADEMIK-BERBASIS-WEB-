<?php
session_start();
include 'db.php'; // Pastikan ini ada

// Tarik semua data dari yang terbaru tanpa filter 'aktif' atau 'limit'
$sql_all_surveys = "SELECT * FROM surveys ORDER BY id_survey DESC";
$result_all_surveys = $conn->query($sql_all_surveys);
?>
<!DOCTYPE html>
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
			color: var(--primary);  
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
			color: #2E1065FF;
	        transition: 0.3s ease;
		}

		.container .navbar ul li a span {
			font-size: 14px;
	        font-weight: 500;
	        letter-spacing: 0.3px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.1);
		}

		.container .navbar ul li a i {
            color:#2E1065FF;
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
           margin-left: 240px; 
            padding: 20px;
            width: calc(100% - 240px);
            color: var(--text);
            transition: margin-left 0.4s ease, width 0.4s ease;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
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

/* LAYOUT KIRI KANAN */
.content-row {
	display: grid;
	grid-template-columns: 2fr 1fr;
	gap: 20px;
	margin-top: 20px;
}

/* --- CSS BARU UNTUK DAFTAR & RIWAYAT SURVEY --- */

/* MAIN CONTENT STYLES */
       

        .sidebar.active ~ .main-content {
            margin-left: var(--sidebar-collapsed);
        }

        .header-title {
            margin-bottom: 30px;
        }

        .header-title h1 {
            font-size: 28px;
            font-weight: 700;
        }

        /* SURVEY CARDS */
        .content-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 25px;
        }

        .section-card {
            background: rgba(255, 255, 255, 0.7);
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--white);
        }

        .section-card h2 {
            font-size: 18px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .survey-item {
            background: var(--white);
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s;
            border: 1px solid transparent;
        }

        .survey-item:hover {
            transform: translateY(-3px);
            border-color: var(--accent);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background: var(--bg);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            font-size: 24px;
        }

        .survey-details {
            flex-grow: 1;
        }

        .survey-details h4 {
            font-size: 15px;
            margin-bottom: 5px;
        }

        .survey-details p {
            font-size: 13px;
            color: #6B7280;
            margin-bottom: 8px;
        }

        .date-tag {
            font-size: 11px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            color: #9CA3AF;
        }

        .survey-status {
            text-align: right;
            min-width: 120px;
        }

        /* BADGES & BUTTONS */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 600;
            margin-bottom: 10px;
        }

        .badge-warning { background: #FEF3C7; color: #D97706; }
        .badge-success { background: #D1FAE5; color: #059669; }

        .btn {
            padding: 8px 16px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
            border: none;
            width: 100%;
        }

        .btn-primary { background: var(--primary); color: var(--white); }
        .btn-primary:hover { background: var(--secondary); }

        .btn-outline { 
            background: transparent; 
            border: 1.5px solid var(--primary); 
            color: var(--primary); 
        }
        .btn-outline:hover { background: var(--primary); color: var(--white); }

        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 12px;
            color: #6B7280;
        }

        @media (max-width: 1024px) {
            .content-grid { grid-template-columns: 1fr; }
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
					<a href="dashboard-user.php"><i class="bi bi-house"></i><span>DashBoard</span></a></li>
				<li><a href="#"><i class="bi bi-journal-text"></i><span>Daftar Survey</span></a></li>
				<li><a href="riwayat-survey-user.php"><i class="bi bi-clock-history"></i><span>Riwayat Survey</span></a></li>
		</div>


		<div class="navbar log-out">
			<ul>
				<li><a href="logout.php"><i class="bi bi-box-arrow-in-right"></i><span>Log Out</span></a></li>
			</ul>
		</div>
	</div>
                <!-- ISI DI SINII         -->
                 <!-- MAIN CONTENT -->
    <main class="main-content">
        <header class="header-title">
            <h1>Selamat Datang Di Daftar Survey</h1>
            <p>Silakan isi aspirasi Anda untuk kemajuan akademik bersama.</p>
        </header>

        <div class="content-grid">
            <!-- LEFT COLUMN -->
            <div class="section-card">
    <h2><i class="bi bi-clipboard2-data"></i> Survey Tersedia</h2>

    <?php
    // Memeriksa apakah ada data kuesioner dari database
    if ($result_all_surveys && $result_all_surveys->num_rows > 0) {
        while ($row = $result_all_surveys->fetch_assoc()) {
            // Ambil data dari database dan ubah format tanggal rilisnya
            $tgl_mulai   = date('d M', strtotime($row['tanggal_mulai']));
            $tgl_selesai = date('d M Y', strtotime($row['tanggal_selesai']));
            $periode_tgl = $tgl_mulai . " - " . $tgl_selesai;
            ?>
            
            <!-- Item Survey Diulang Dinamis Sesuai Data Admin -->
            <div class="survey-item">
                <div class="icon-box"><i class="bi bi-person-badge"></i></div>
                <div class="survey-details">
                                <h4><?= htmlspecialchars($row['judul_survey']); ?></h4>
                                <p><?= htmlspecialchars($row['deskripsi']); ?></p>
                                
                                <!-- INI BARIS BARU UNTUK TARGET RESPONDEN (PRODI) -->
                                <p style="font-size: 12px; color: var(--secondary); margin-top: -3px; margin-bottom: 8px; display: flex; align-items: center; gap: 5px;">
                                    <i class="bi bi-mortarboard" style="font-size: 14px;"></i> 
                                    <strong>Target Responden:</strong> <?= htmlspecialchars($row['target_prodi']); ?>
                                </p>

                                <span class="date-tag"><i class="bi bi-calendar-event"></i> <?= $periode_tgl; ?></span>
                            </div>
                <div class="survey-status">
                    <?php 
                    // Ambil status dari database, jika tidak ada default ke aktif
                    $status = isset($row['status']) ? strtolower($row['status']) : 'aktif';
                    
                    if ($status == 'aktif'): ?>
                        <span class="badge badge-warning">Belum Diisi</span>
                        <a href="isi-survey.php?id=<?= $row['id_survey']; ?>" class="btn btn-primary" style="text-decoration:none; display:block;">Isi Sekarang</a>
                    <?php else: ?>
                        <span class="badge badge-danger">Ditutup</span>
                        <button class="btn btn-outline" disabled style="opacity: 0.5; cursor: not-allowed;">Selesai</button>
                    <?php endif; ?>
                </div>
            </div>

            <?php
        } // Akhir perulangan while
    } else {
        // Jika admin belum membuat survey sama sekali
        echo "<p style='text-align:center; color:#9CA3AF; padding: 20px;'>Belum ada kuesioner survey yang tersedia saat ini.</p>";
    }
    ?>
</div>

            
        </div>

        <div class="footer">
            <p>&copy; 2026 Ruang Aspirasi Akademik. All rights reserved. | Dibuat untuk Evaluasi Mahasiswa</p>
        </div>
    </main>

    <script>
        var toggle = document.getElementById("toggle");
        var container = document.getElementById("container");

        toggle.onclick = function () {
            container.classList.toggle('active');
        }
    </script>
</body>
</html>