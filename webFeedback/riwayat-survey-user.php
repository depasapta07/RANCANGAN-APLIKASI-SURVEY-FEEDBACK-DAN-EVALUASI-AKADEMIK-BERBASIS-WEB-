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
            filter: drop-shadow(0px 2px 3px rgba(76, 46, 121, 0.2));
            
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
			color: #2E1065F;  
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
            color: #2E1065FF;
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
    margin-left: 240px; 
    padding: 20px 30px; /* Sedikit dikurangi agar tidak sesak */
    width: calc(100% - 240px);
    color: var(--text); 
    transition: 0.4s ease; 
    display: flex; 
    flex-direction: column; 
    height: 100vh; /* Mengunci tinggi tepat sama dengan layar */
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

        .main-content {
            margin-left: 240px; padding: 30px 40px; width: calc(100% - 240px);
            color: var(--text); transition: 0.4s ease; display: flex; flex-direction: column; min-height: 100vh;
            overflow-y: auto;
        }
        .container.active ~ .main-content { margin-left: 80px; width: calc(100% - 80px); }

        .header-title { margin-bottom: 25px; }
        .header-title h1 { font-size: 24px; font-weight: 600; }
        .header-title p { font-size: 14px; color: var(--gray-text); margin-top: 5px; }

        /* --- FILTER SECTION --- */
        .filter-row { display: flex; gap: 15px; margin-bottom: 30px; }
        .filter-item { 
            display: flex; align-items: center; background: rgba(255, 255, 255, 0.8); 
            padding: 10px 15px; border-radius: 10px; border: 1px solid rgba(255,255,255,0.5); 
            backdrop-filter: blur(10px); flex: 1; font-size: 13px; color: var(--gray-text);
        }
        .filter-search { flex: 2; }
        .filter-item input, .filter-item select { 
            border: none; outline: none; width: 100%; background: transparent; 
            font-family: inherit; margin-left: 10px; color: var(--text); font-size: 13px;
        }
        .btn-reset {
            background: transparent; border: 1px solid #cbd5e1; padding: 0 15px; border-radius: 10px;
            cursor: pointer; display: flex; align-items: center; gap: 5px; color: var(--gray-text); font-size: 13px; transition: 0.3s;
        }
        .btn-reset:hover { background: var(--gray-bg); }

        /* --- WADAH RIWAYAT (GLASSMORPHISM) --- */
        .history-wrapper {
    background: rgba(255, 255, 255, 0.6); 
    backdrop-filter: blur(15px);
    border-radius: 20px; 
    padding: 20px 25px; /* Disesuaikan */
    border: 1px solid var(--white);
    box-shadow: 0 10px 30px rgba(0,0,0,0.05); 
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden; /* Supaya pembungkus luar tidak ikut jebol ke bawah */
    margin-bottom: 10px;
}

        .history-wrapper h2 { font-size: 18px; margin-bottom: 25px; color: var(--text); }

        /* --- SISTEM GRID BIAR LURUS --- */
        .grid-row { 
            display: grid; 
            grid-template-columns: 2.5fr 1fr 1.2fr 1.5fr 1fr 1.2fr; /* Rasio 6 Kolom */
            gap: 15px; align-items: center; 
        }
        .grid-header { font-weight: 600; color: var(--gray-text); font-size: 13px; padding: 0 20px; margin-bottom: 15px; }

        /* --- DESAIN CARD --- */
        .history-card { 
            background: var(--white); border: 1px solid transparent; border-radius: 12px; 
            padding: 15px 20px; margin-bottom: 12px; transition: 0.3s; 
        }
        .history-card:hover { 
            box-shadow: 0 5px 15px rgba(139, 92, 246, 0.15); border-color: var(--accent); transform: translateY(-2px); 
        }

        /* Isi Kolom Card */
        .col-mk { display: flex; align-items: center; gap: 15px; }
        .mk-icon { 
            width: 45px; height: 45px; border-radius: 10px; display: flex; 
            justify-content: center; align-items: center; font-size: 20px; flex-shrink: 0; 
        }
        /* Warna Icon */
        .bg-blue { background: #eff6ff; color: #3b82f6; }
        .bg-green { background: #f0fdf4; color: #22c55e; }
        .bg-purple { background: #faf5ff; color: #a855f7; }
        .bg-orange { background: #fff7ed; color: #f97316; }
        .bg-teal { background: #f0fdfa; color: #14b8a6; }

        .mk-info h4 { font-size: 14px; font-weight: 600; margin-bottom: 2px; }
        .mk-info p { font-size: 12px; color: var(--gray-text); margin-bottom: 4px; }
        .mk-info .dept { font-size: 11px; color: var(--primary); display: flex; align-items: center; gap: 4px; font-weight: 500; }

        .col-text { font-size: 13px; font-weight: 500; }
        .col-text span { display: block; font-size: 12px; color: var(--gray-text); font-weight: 400; margin-top: 2px; }

        /* Status & Progress */
        .status-col { display: flex; flex-direction: column; align-items: flex-start; gap: 6px; }
.status-badge { 
    padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; 
    display: inline-flex; align-items: center; gap: 5px; border: none;
}
.badge-selesai { background: #dcfce7; color: #16a34a; }
.badge-belum { background: #fef3c7; color: #d97706; }
.badge-kosong { background: #f1f5f9; color: #64748b; } /* Background abu-abu halus */
.badge-tutup { background: #fee2e2; color: #dc2626; }
.status-desc { font-size: 12px; color: #64748b; line-height: 1.4; }

.progress-col { display: flex; flex-direction: column; gap: 6px; width: 90%; }

        .prog-text { font-size: 13px; font-weight: 600; margin-bottom: 5px; }
        .prog-bar-bg { width: 90%; height: 6px; background: #e2e8f0; border-radius: 10px; }
        .prog-fill { height: 100%; border-radius: 10px; }

        /* Tombol Aksi */
        .btn-action { 
            padding: 8px 12px; border-radius: 8px; font-size: 12px; font-weight: 500; 
            cursor: pointer; text-align: center; text-decoration: none; display: flex; 
            justify-content: center; align-items: center; gap: 5px; width: 100%; transition: 0.3s; 
        }
        .btn-outline { border: 1px solid var(--primary); color: var(--primary); }
        .btn-outline:hover { background: var(--primary); color: var(--white); }
        .btn-solid { background: var(--primary); color: var(--white); border: 1px solid var(--primary); }
        .btn-solid:hover { background: var(--secondary); }
        .btn-disabled { background: var(--gray-bg); color: #94a3b8; cursor: not-allowed; border: 1px solid #e2e8f0; }

        /* --- PAGINATION --- */
        .pagination { 
            display: flex; justify-content: space-between; align-items: center; 
            margin-top: 20px; padding-top: 20px; border-top: 1px dashed #cbd5e1; font-size: 13px; color: var(--gray-text); 
        }
        .page-numbers { display: flex; gap: 5px; }
        .page-btn { 
            width: 32px; height: 32px; border: 1px solid #e2e8f0; border-radius: 8px; 
            background: var(--white); cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.3s;
        }
        .page-btn.active { background: var(--primary); color: var(--white); border-color: var(--primary); }
        .page-btn:hover:not(.active) { background: var(--gray-bg); }
        .page-limit select { border: 1px solid #e2e8f0; border-radius: 6px; padding: 4px; outline: none; margin: 0 5px; }

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

/* --- KUNCI BIAR KARTU BISA SCROLL DI DALAM KOTAK PUTIH (JIKA DATA BANYAK) --- */
.history-cards-container {
    flex-grow: 1;
    overflow-y: auto;
    padding-right: 5px; /* Ruang nafas untuk scrollbar internal */
    margin-bottom: 15px;
}

/* Opsional: Membuat tampilan scrollbar internal jadi lebih aesthetic & tipis */
.history-cards-container::-webkit-scrollbar {
    width: 6px;
}
.history-cards-container::-webkit-scrollbar-track {
    background: transparent;
}
.history-cards-container::-webkit-scrollbar-thumb {
    background: rgba(139, 92, 246, 0.3);
    border-radius: 10px;
}
.history-cards-container::-webkit-scrollbar-thumb:hover {
    background: rgba(139, 92, 246, 0.5);
}

/* Menyesuaikan margin footer agar pas di bawah */
.footer {
    margin-top: auto;
    padding-top: 10px;
    padding-bottom: 10px;
    border-top: 1px solid #E5E7EB;
    text-align: center;
    color: #6B7280;
    font-size: 13px;
    width: 100%;
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
				<li><a href="daftar-survey-user.php"><i class="bi bi-journal-text"></i><span>Daftar Survey</span></a></li>
				<li><a href="#"><i class="bi bi-clock-history"></i><span>Riwayat Survey</span></a></li>
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
            <h1>Riwayat Survey</h1>
            <p>Berikut adalah riwayat pengisian survey dan feedback evaluasi akademik yang telah Anda lakukan.</p>
        </header>

       

        <!-- AREA LIST RIWAYAT -->
        <div class="history-wrapper">
            <h2>Daftar Riwayat Survey</h2>
             <!-- FITUR FILTER -->
        <div class="filter-row">
            <div class="filter-item filter-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari mata kuliah atau nama dosen...">
            </div>
            <div class="filter-item">
                <i class="bi bi-calendar3"></i>
                <select>
                    <option>Semua Semester</option>
                    <option>Semester 4</option>
                    <option>Semester 3</option>
                </select>
            </div>
            <div class="filter-item">
                <i class="bi bi-funnel"></i>
                <select>
                    <option>Semua Status</option>
                    <option>Selesai</option>
                    <option>Belum Selesai</option>
                </select>
            </div>
            <button class="btn-reset"><i class="bi bi-arrow-clockwise"></i> Reset Filter</button>
        </div>

            <!-- Header Grid (Judul Kolom) -->
            <div class="grid-row grid-header">
                <div>Mata Kuliah & Dosen</div>
                <div>Semester</div>
                <div>Tahun Akademik</div>
                <div>Status</div>
                <div>Progress</div>
                <div style="text-align: center;">Aksi</div>
            </div>

            <div class="history-cards-container">
            <!-- Card 1 -->
            <div class="history-card grid-row">
                <div class="col-mk">
                    <div class="mk-icon bg-blue"><i class="bi bi-code-slash"></i></div>
                    <div class="mk-info">
                        <h4>Pemrograman Web</h4>
                        <p>Pak Budi Santoso, S.Kom., M.Kom.</p>
                        <span class="dept"><i class="bi bi-bank"></i> Teknik Informatika</span>
                    </div>
                </div>
                <div class="col-text">Semester 4</div>
                <div class="col-text">2025/2026<span>Genap</span></div>
                <div class="status-col">
                    <span class="status-badge badge-selesai"><i class="bi bi-check-circle-fill"></i> Selesai</span>
                    <span class="status-desc">Diisi pada<br>12 Mei 2025</span>
                </div>
                <div class="progress-col">
                    <div class="prog-text" style="color: var(--success-text);">100%</div>
                    <div class="prog-bar-bg"><div class="prog-fill" style="background: var(--success-text); width: 100%;"></div></div>
                </div>
                <div><a href="#" class="btn-action btn-outline"><i class="bi bi-eye"></i> Lihat Hasil</a></div>
            </div>

            <!-- Card 2 -->
            <div class="history-card grid-row">
                <div class="col-mk">
                    <div class="mk-icon bg-green"><i class="bi bi-database"></i></div>
                    <div class="mk-info">
                        <h4>Basis Data</h4>
                        <p>Bu Sari Mulyani, S.T., M.Kom.</p>
                        <span class="dept"><i class="bi bi-bank"></i> Teknik Informatika</span>
                    </div>
                </div>
                <div class="col-text">Semester 4</div>
                <div class="col-text">2025/2026<span>Genap</span></div>
                <div class="status-col">
                    <span class="status-badge badge-belum"><i class="bi bi-clock-fill"></i> Belum Selesai</span>
                    <span class="status-desc">Terakhir diisi<br>10 Mei 2025</span>
                </div>
                <div class="progress-col">
                    <div class="prog-text" style="color: var(--warning-text);">60%</div>
                    <div class="prog-bar-bg"><div class="prog-fill" style="background: var(--warning-text); width: 60%;"></div></div>
                </div>
                <div><a href="#" class="btn-action btn-solid"><i class="bi bi-pencil-square"></i> Lanjutkan</a></div>
            </div>

            <!-- Card 3 -->
            <div class="history-card grid-row">
                <div class="col-mk">
                    <div class="mk-icon bg-purple"><i class="bi bi-diagram-3"></i></div>
                    <div class="mk-info">
                        <h4>Sistem Operasi</h4>
                        <p>Pak Andi Wijaya, S.Kom., M.T.</p>
                        <span class="dept"><i class="bi bi-bank"></i> Teknik Informatika</span>
                    </div>
                </div>
                <div class="col-text">Semester 4</div>
                <div class="col-text">2025/2026<span>Genap</span></div>
                <div class="status-col">
                    <span class="status-badge badge-kosong"><i class="bi bi-circle"></i> Belum Diisi</span>
                    <span class="status-desc">Survey belum<br>pernah diisi</span>
                </div>
                <div class="progress-col">
                    <div class="prog-text" style="color: var(--gray-text);">0%</div>
                    <div class="prog-bar-bg"><div class="prog-fill" style="width: 0%;"></div></div>
                </div>
                <div><a href="#" class="btn-action btn-outline"><i class="bi bi-play"></i> Isi Sekarang</a></div>
            </div>

            <!-- Card 4 -->
            <div class="history-card grid-row">
                <div class="col-mk">
                    <div class="mk-icon bg-orange"><i class="bi bi-display"></i></div>
                    <div class="mk-info">
                        <h4>Jaringan Komputer</h4>
                        <p>Pak Rudi Hermawan, S.T., M.T.</p>
                        <span class="dept"><i class="bi bi-bank"></i> Teknik Informatika</span>
                    </div>
                </div>
                <div class="col-text">Semester 3</div>
                <div class="col-text">2024/2025<span>Ganjil</span></div>
                <div class="status-col">
                    <span class="status-badge badge-tutup"><i class="bi bi-x-circle-fill"></i> Ditutup</span>
                    <span class="status-desc">Ditutup pada<br>20 Des 2024</span>
                </div>
                <div class="progress-col">
                    <div class="prog-text" style="color: var(--gray-text);">-</div>
                </div>
                <div><div class="btn-action btn-disabled"><i class="bi bi-lock-fill"></i> Ditutup</div></div>
            </div>
</div>

            <!-- PAGINATION -->
           

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