<?php
session_start(); // Mulai sesi untuk baca data 

// Kosongkan semua data session
$_SESSION = [];

// Hapus session dari memori server
session_unset();

// Hancurkan session sepenuhnya
session_destroy();

// Balikkan ke halaman utama (Home)
// Alert opsional biar tau kalau udah keluar
echo "<script>
        alert('Anda telah berhasil Logout.');
        window.location = '../index.php';
      </script>";
exit;