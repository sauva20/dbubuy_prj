<?php
session_start(); // Mulai sesi untuk baca data yg ada

// 1. Kosongkan semua data session
$_SESSION = [];

// 2. Hapus session dari memori server
session_unset();

// 3. Hancurkan session sepenuhnya
session_destroy();

// 4. Balikkan ke halaman utama (Home)
// Alert opsional biar tau kalau udah keluar
echo "<script>
        alert('Anda telah berhasil Logout.');
        window.location = '../index.php';
      </script>";
exit;