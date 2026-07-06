<?php
session_start();
include 'db.php';

$error = "";

// Cek apakah form login sudah disubmit
if (isset($_POST['login'])) {
    // Input ini bisa berupa "username" (nama_mhs) atau "npm"
    $username_or_npm = $_POST['username']; 
    $password = $_POST['password'];

    // 1. Pertama, periksa tabel 'admins'
    $sql_admin = "SELECT * FROM admins WHERE username = ?";
    $stmt = $conn->prepare($sql_admin);
    $stmt->bind_param("s", $username_or_npm);
    $stmt->execute();
    $result_admin = $stmt->get_result();

    if ($result_admin->num_rows > 0) {
        $admin = $result_admin->fetch_assoc();
        
        // Verifikasi password admin
        if ($password == $admin['password']) {
            $_SESSION['admin_id'] = $admin['id_admin'];
            $_SESSION['username'] = $admin['username'];
            $_SESSION['role'] = 'admin'; 
            
            header("Location: admin.php");
            exit();
        } else {
            $error = "<b>Akun Pengguna</b> atau <b>Kata Sandi</b> tidak sesuai.";
        }
    } else {
        // 2. PERBAIKAN: Cari di tabel mahasiswa berdasarkan 'npm' ATAU 'nama_mhs'
        $sql_mhs = "SELECT * FROM mahasiswa WHERE npm = ? OR nama_mhs = ?";
        $stmt = $conn->prepare($sql_mhs);
        // Karena kita punya dua tanda tanya (?) di query, kita kirimkan variabelnya dua kali
        $stmt->bind_param("ss", $username_or_npm, $username_or_npm);
        $stmt->execute();
        $result_mhs = $stmt->get_result();

        if ($result_mhs->num_rows > 0) {
            $mhs = $result_mhs->fetch_assoc();
            
            // Verifikasi password mahasiswa
            if ($password == $mhs['password']) {
    $_SESSION['user_id'] = $mhs['id_mahasiswa'];
    $_SESSION['npm'] = $mhs['npm'];
    $_SESSION['nama'] = $mhs['nama_mhs']; // Disamakan menjadi 'nama' agar terbaca di dashboard
    $_SESSION['prodi'] = $mhs['prodi'];    // TAMBAHAN: Menyimpan prodi ke session (pastikan nama kolom di DB adalah 'prodi')
    $_SESSION['role'] = 'mahasiswa';
    
    header("Location: dashboard-user.php");
                exit();
            } else {
                $error = "<b>Akun Pengguna</b> atau <b>Kata Sandi</b> tidak sesuai.";
            }
        } else {
            $error = "<b>Akun Pengguna</b> atau <b>Kata Sandi</b> tidak sesuai.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login User</title>
  <!-- Sertakan Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <style>
    @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;600;700&display=swap");

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: "Roboto", sans-serif;
    }

    body {
      background: linear-gradient(135deg, #F5F3FF, #EDE9FE);
      min-height: 100vh;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0.5;
      width: 100%;
      height: 100%;
      background-position: center;
      background-size: cover;
      z-index: -1;
    }

    nav {
      position: fixed;
      padding: 25px 60px;
      z-index: 1;
    }

    nav a img {
      width: 167px;
    }

    .form-wrapper {
      position: absolute;
      left: 50%;
      top: 50%;
      border-radius: 12px;
      padding: 70px;
      width: 450px;
      transform: translate(-50%, -50%);
      
      /* TRANSPARAN + GLASS EFFECT */
      background: rgba(255, 255, 255, 0.7);
      backdrop-filter: blur(15px);
      -webkit-backdrop-filter: blur(15px);

      /* Tambahan biar lebih elegan */
      border: 1px solid rgba(139, 92, 246, 0.2);
      box-shadow: 0 8px 30px rgba(139, 92, 246, 0.2);
    }

    .form-wrapper h2 {
      color: var(--secondary);
      font-size: 2rem;
      margin-bottom: 20px;
      text-align: center; /* MENGUBAH TULISAN LOGIN KE TENGAH */
    }

    .form-wrapper form {
      margin: 25px 0 65px;
    }

    form .form-control {
      height: 50px;
      position: relative;
      margin-bottom: 16px;
    }

    .form-control input {
      height: 100%;
      width: 100%;
      background: #F5F3FF;      
      border: none;
      outline: none;
      border: 1px solid #ddd;
      border-radius: 4px;
      color: var(--text);
      font-size: 1rem;
      padding: 0 20px;
    }

    .form-control input:focus {
      border-color: var(--primary);
      background: #fff;
    }
    
    .form-control input:is(:focus, :valid) {
      background: #F5F3FF;  
      padding: 16px 20px 0;
    }

    .form-control label {
      position: absolute;
      left: 20px;
      top: 50%;
      transform: translateY(-50%);
      font-size: 1rem;
      pointer-events: none;
       color: #6B7280;
      transition: all 0.1s ease;
    }

    .form-control input:is(:focus, :valid) ~ label {
      font-size: 0.75rem;
      transform: translateY(-130%);
    }

    form button {
      width: 100%;
      padding: 16px 0;
      font-size: 1rem;
       background: linear-gradient(135deg, #8B5CF6, #A78BFA);
       color: white;
       font-weight: 500;
        border-radius: 8px;
      border: none;
      outline: none;
      margin: 25px 0 10px;
      cursor: pointer;
      transition: 0.3s;
    }

    form button:hover {
      box-shadow: 0 5px 15px rgba(139, 92, 246, 0.4);
    }

    .form-wrapper a {
      text-decoration: none;
    }

    .form-wrapper a:hover {
      text-decoration: underline;
    }

    .form-wrapper :where(label, p, small, a) {
      color: #b3b3b3;
    }

    form .form-help {
      display: flex;
      justify-content: space-between;
    }

    form .remember-me {
      display: flex;
    }

    form .remember-me input {
      margin-right: 5px;
      accent-color: #b3b3b3;
    }

    form .form-help :where(label, a) {
      font-size: 0.9rem;
    }

    .form-wrapper p {
      color: #6B7280;
      text-align: center;
    }

    .form-wrapper a {
      color: var(--primary);
      font-weight: 500;
    }

    .form-wrapper small {
      display: block;
      margin-top: 15px;
      color: #b3b3b3;
    }

    .form-wrapper small a {
      color: #0071eb;
    }

    /* PESAN ERROR TERBARU SESUAI GAMBAR */
    .error-message {
      background-color: #E74C3C; 
      color: #ffffff;
      padding: 10px 40px 10px 15px; 
      border-radius: 6px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 0.85rem; 
      position: relative;
    }

    .error-message b {
      font-weight: 600; 
    }

    /* Tombol Close (x) pada pesan error */
    .error-message .close-btn {
      position: absolute;
      top: 50%;
      right: 15px;
      transform: translateY(-50%);
      color: rgba(255, 255, 255, 0.7);
      cursor: pointer;
      font-size: 1rem;
      transition: color 0.2s;
    }

    .error-message .close-btn:hover {
      color: #ffffff;
    }

    @media (max-width: 740px) {
      body::before {
        display: none;
      }
      nav,
      .form-wrapper {
        padding: 20px;
      }
      nav a img {
        width: 140px;
      }
      .form-wrapper {
        width: 100%;
        top: 43%;
      }
      .form-wrapper form {
        margin: 25px 0 40px;
      }
    }
  </style>
</head>
<body>
<div class="form-wrapper">
    <h2>Login</h2>
    
    <!-- Area untuk menampilkan pesan error -->
    <?php if (!empty($error)) : ?>
      <div class="error-message" id="errorBox">
        <?php echo $error; ?>
        <!-- Menggunakan icon X dari FontAwesome yang sudah kamu pasang dan fungsi JS onclick untuk menutup -->
        <i class="fas fa-times close-btn" onclick="document.getElementById('errorBox').style.display='none'"></i>
      </div>
    <?php endif; ?>

    <form action="" method="POST">
      <div class="form-control">
        <!-- Input ini menangkap Username ATAU NPM -->
        <input type="text" name="username" required>
        <label>Masukan Username / NPM</label>
      </div>
      <div class="form-control">
        <input type="password" name="password" required>
        <label>Masukan Password</label>
      </div>
      <button type="submit" name="login" class="login-btn">Login</button>
    </form>

    <p>Belum punya akun? <a href="register.php">Daftar Sekarang</a></p>
  </div>
</body>
</html>