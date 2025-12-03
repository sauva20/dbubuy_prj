<?php
session_start();
include '../config/koneksi.php';

// Pastikan ada request post
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $action = $_POST['action'];

    // LOGIKA REGISTER
    if ($action == 'register') {
        
        $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
        $no_whatsapp  = mysqli_real_escape_string($koneksi, $_POST['no_whatsapp']);
        $password     = $_POST['password'];
        $pass_repeat  = $_POST['password_repeat'];

        // Validasi Password Sama
        if ($password !== $pass_repeat) {
            echo "<script>alert('Konfirmasi password tidak cocok!'); window.location='../pages/register.php';</script>";
            exit;
        }

        // Cek apakah No WA sudah terdaftar?
        $cek_wa = mysqli_query($koneksi, "SELECT * FROM users WHERE no_whatsapp = '$no_whatsapp'");
        if (mysqli_num_rows($cek_wa) > 0) {
            echo "<script>alert('Nomor WhatsApp sudah terdaftar! Silakan login.'); window.location='../pages/login.php';</script>";
            exit;
        }

        // Hash Password
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        // Input ke Database (Default kategori = customer)
        $query_reg = "INSERT INTO users (nama_lengkap, username, no_whatsapp, password, kategori, is_banned) 
                      VALUES ('$nama_lengkap', '$nama_lengkap', '$no_whatsapp', '$password_hash', 'customer', 0)";

        if (mysqli_query($koneksi, $query_reg)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='../pages/login.php';</script>";
        } else {
            echo "<script>alert('Gagal Registrasi: " . mysqli_error($koneksi) . "'); window.location='../pages/register.php';</script>";
        }
    }

    // LOGIKA LOGIN
    elseif ($action == 'login') {
        
        $no_whatsapp = mysqli_real_escape_string($koneksi, $_POST['no_whatsapp']);
        $password    = $_POST['password'];

        // Cek User di Db
        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE no_whatsapp='$no_whatsapp'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);

            // CEK STATUS BANNED 
            if ($data['is_banned'] == 1) {
                echo "<script>
                        alert('Akun Anda telah DIBLOKIR karena pelanggaran. Silakan hubungi Admin.');
                        window.location='../pages/login.php';
                      </script>";
                exit; 
            }

            // Cek Password
            if (password_verify($password, $data['password'])) {
                
                // Simpan Session
                $_SESSION['is_login'] = true;
                $_SESSION['user_id']  = $data['id'];
                $_SESSION['nama']     = $data['nama_lengkap'];
                $_SESSION['username'] = $data['username']; // fitur profile & checkout
                $_SESSION['kategori'] = $data['kategori']; 

                // Redirect
                if ($data['kategori'] == 'admin') {
                    header("Location: ../backend/admin/index_admin.php");
                } else {
                    header("Location: ../index.php");
                }
                exit;

            } else {
                echo "<script>alert('Password Salah!'); window.location='../pages/login.php';</script>";
            }
        } else {
            echo "<script>alert('Akun tidak ditemukan! Silakan daftar.'); window.location='../pages/register.php';</script>";
        }
    }

} else {
    // Jika akses langsung ke file ini tanpa POST
    header("Location: ../pages/login.php");
}
?>