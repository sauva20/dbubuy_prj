<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "si_gudang"; // Sesuai database

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi Database Gagal: " . mysqli_connect_error());
}
// --- FUNGSI PENCATAT LOG AKTIVITAS (CCTV ADMIN) ---
function catat_log($koneksi, $action, $details) {
    // Cek apakah user sedang login
    if (isset($_SESSION['is_login'])) {
        $uid   = $_SESSION['user_id'] ?? 0;
        $uname = $_SESSION['nama'] ?? 'System';
        $ip    = $_SERVER['REMOTE_ADDR'];
        
        // Bersihkan input biar aman dari error kutip
        $action  = mysqli_real_escape_string($koneksi, $action);
        $details = mysqli_real_escape_string($koneksi, $details);
        
        // Masukkan ke tabel logs yang barusan kamu buat
        $q_log = "INSERT INTO logs (user_id, username, action, details, ip_address) 
                  VALUES ('$uid', '$uname', '$action', '$details', '$ip')";
        mysqli_query($koneksi, $q_log);
    }
}


?>