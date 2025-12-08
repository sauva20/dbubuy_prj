<?php
session_start();
include '../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    // --- LOGIKA REGISTER ---
    if ($action == 'register') {
        $nama_lengkap = mysqli_real_escape_string($koneksi, $_POST['nama_lengkap']);
        $no_whatsapp  = mysqli_real_escape_string($koneksi, $_POST['no_whatsapp']);
        $password     = $_POST['password'];
        $pass_repeat  = $_POST['password_repeat'];

        // 1. Validasi Password
        if ($password !== $pass_repeat) {
            header("Location: /pages/auth.php?status=fail_pass_mismatch&trigger=register");
            exit;
        }

        // 2. Cek User Terdaftar
        $cek_wa = mysqli_query($koneksi, "SELECT * FROM users WHERE no_whatsapp = '$no_whatsapp'");
        if (mysqli_num_rows($cek_wa) > 0) {
            // Sudah punya akun? Lempar ke Login
            header("Location: /pages/auth.php?status=fail_exist&trigger=login");
            exit;
        }

        // 3. Insert Data
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query_reg = "INSERT INTO users (nama_lengkap, username, no_whatsapp, password, kategori, is_banned) 
                      VALUES ('$nama_lengkap', '$nama_lengkap', '$no_whatsapp', '$password_hash', 'customer', 0)";

        if (mysqli_query($koneksi, $query_reg)) {
            // Sukses daftar? Lempar ke Login
            if(function_exists('catat_log')) catat_log($koneksi, "Register", "User baru: $nama_lengkap");
            header("Location: /pages/auth.php?status=success_register&trigger=login");
        } else {
            header("Location: /pages/auth.php?status=fail_db&trigger=register");
        }
    }

    // --- LOGIKA LOGIN ---
    elseif ($action == 'login') {
        $no_whatsapp = mysqli_real_escape_string($koneksi, $_POST['no_whatsapp']);
        $password    = $_POST['password'];

        $query = mysqli_query($koneksi, "SELECT * FROM users WHERE no_whatsapp='$no_whatsapp'");
        
        if (mysqli_num_rows($query) > 0) {
            $data = mysqli_fetch_assoc($query);

            // Cek Banned
            if ($data['is_banned'] == 1) {
                header("Location: /pages/auth.php?status=banned&trigger=login");
                exit; 
            }

            // Cek Password
            if (password_verify($password, $data['password'])) {
                $_SESSION['is_login'] = true;
                $_SESSION['user_id']  = $data['id'];
                $_SESSION['nama']     = $data['nama_lengkap'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['kategori'] = $data['kategori']; 

                if(function_exists('catat_log')) catat_log($koneksi, "Login", "User login");

                if ($data['kategori'] == 'admin') {
                    header("Location: ../backend/admin/index_admin.php");
                } else {
                    header("Location: ../index.php");
                }
                exit;

            } else {
                // Password Salah -> Tetap di Login
                header("Location: /pages/auth.php?status=fail_password&trigger=login");
            }
        } else {
            // AKUN TIDAK DITEMUKAN -> LEMPAR KE REGISTER (Geser Slide Otomatis)
            header("Location: /pages/auth.php?status=not_found&trigger=register");
        }
    }
} else {
    header("Location: /pages/auth.php");
}
?>