<?php
session_start();
require_once '../config/koneksi.php';

// [BARU] CATAT LOG SEBELUM SESSION DIHANCURKAN
if (isset($_SESSION['is_login'])) {
    if(function_exists('catat_log')){
        catat_log($koneksi, "Logout", "User keluar dari sistem");
    }
}

// Hapus semua session
session_unset();
session_destroy();

// Redirect ke halaman login

echo "<script>
        alert('Anda telah berhasil Logout.');
        window.location = '../index.php';
      </script>";
exit;