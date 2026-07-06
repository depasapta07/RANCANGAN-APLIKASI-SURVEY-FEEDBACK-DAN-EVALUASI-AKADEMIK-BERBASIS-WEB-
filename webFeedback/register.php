<?php 
session_start();
include 'db.php';

$message = ""; // Variabel untuk menampung pesan error/sukses

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form dan sesuaikan dengan kolom di tabel mahasiswa
    $nama_mhs = htmlspecialchars(trim($_POST['nama_mhs']));
    $npm = htmlspecialchars(trim($_POST['npm']));
    $password = htmlspecialchars(trim($_POST['password']));
    $prodi = htmlspecialchars(trim($_POST['prodi']));

    // Validasi input jika ada yang kosong
    if (empty($nama_mhs) || empty($npm) || empty($password) || empty($prodi)) {
        $message = "<p class='error-message'>Semua kolom harus diisi.</p>";
    } else {
        // Cek apakah NPM sudah terdaftar sebelumnya
        $cek_npm = "SELECT npm FROM mahasiswa WHERE npm = ?";
        $stmt_cek = $conn->prepare($cek_npm);
        $stmt_cek->bind_param("s", $npm);
        $stmt_cek->execute();
        $stmt_cek->store_result();

        if ($stmt_cek->num_rows > 0) {
            $message = "<p class='error-message'>NPM sudah terdaftar! Silakan gunakan NPM lain.</p>";
        } else {
            // Catatan: Sesuai dengan sistem loginmu yang mencocokkan password secara langsung (tanpa hash), 
            // maka di sini kita simpan password apa adanya. (Untuk ke depannya, sangat disarankan menggunakan password_hash).
            $plain_password = $password;

            // Query untuk memasukkan data ke tabel mahasiswa
            $sql = "INSERT INTO mahasiswa (npm, password, nama_mhs, prodi) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                // "ssss" berarti ada 4 string yang dimasukkan
                $stmt->bind_param("ssss", $npm, $plain_password, $nama_mhs, $prodi);

                if ($stmt->execute()) {
                    // Redirect ke halaman login jika sukses
                    header('Location: login.php'); 
                    exit();
                } else {
                    $message = "<p class='error-message'>Terjadi kesalahan: " . $stmt->error . "</p>";
                }
                $stmt->close();
            } else {
                $message = "<p class='error-message'>Terjadi kesalahan: " . $conn->error . "</p>";
            }
        }
        $stmt_cek->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Register</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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
      /* background: url("img/bgron.jpeg"); */
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
      text-align: center;
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

    /* Pesan Error */
    .error-message {
      color: #DC2626;
      font-weight: bold;
      margin-bottom: 10px;
      text-align: center;
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
    <h2>Register</h2>
    
    <!-- Tampilkan pesan error di sini -->
    <?php if(!empty($message)) echo $message; ?>

    <form action="" method="POST">
      <div class="form-control">
        <input type="text" name="nama_mhs" required>
        <label>Masukkan Nama Lengkap</label>
      </div>
      <div class="form-control">
        <input type="text" name="npm" required>
        <label>Masukkan NPM</label>
      </div>
      <div class="form-control">
        <input type="password" name="password" required>
        <label>Masukkan Password</label>
      </div>
      <div class="form-control">
        <input type="text" name="prodi" required>
        <label>Masukkan Program Studi</label>
      </div>
      <button type="submit">Register</button>
    </form>
    
    <p>Sudah Punya Akun? <a href="login.php">Masuk Sekarang</a></p>
  </div>
</body>

</html>
