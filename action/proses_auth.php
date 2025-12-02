<?php
session_start();
include '../config/koneksi.php';



// ... setelah query user berhasil diambil ($row) ...

// CEK STATUS BANNED
if ($row['is_banned'] == 1) {
    echo "<script>
            alert('Akun Anda telah DIBLOKIR karena pelanggaran. Silakan hubungi Admin.');
            window.location='../pages/login.php';
          </script>";
    exit; // Stop proses login
}

// ... baru lanjut verifikasi password ...
if (password_verify($password, $row['password'])) {
    // ... proses login sukses ...
}

// Pastikan tombol login ditekan
if (isset($_POST['action']) && $_POST['action'] == 'login') {

    $no_whatsapp = $_POST['no_whatsapp'];
    $password    = $_POST['password'];

    // 1. Cek User
    $query = mysqli_query($koneksi, "SELECT * FROM users WHERE no_whatsapp='$no_whatsapp'");
    
    if (mysqli_num_rows($query) > 0) {
        $data = mysqli_fetch_assoc($query);

        // 2. Cek Password (Verify Hash)
        if (password_verify($password, $data['password'])) {
            
            // Simpan Data ke Session
            $_SESSION['is_login'] = true;
            $_SESSION['user_id']  = $data['id'];
            $_SESSION['nama']     = $data['nama_lengkap'];
            $_SESSION['kategori'] = $data['kategori']; // admin atau customer

            // 3. Redirect Sesuai Kategori
            if ($data['kategori'] == 'admin') {
                // Ke Dashboard Admin
                header("Location: ../backend/admin/index_admin.php");
            } else {
                // Ke Halaman Depan
                header("Location: ../index.php");
            }
            exit;

        } else {
            header("Location: ../pages/login.php?error=password_salah");
        }
    } else {
        header("Location: ../pages/login.php?error=user_tidak_ditemukan");
    }
} else {
    header("Location: ../pages/login.php");
}
?>