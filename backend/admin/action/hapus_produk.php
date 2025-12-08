<?php
session_start();
// Gunakan include agar catat_log bisa dipakai
require_once '../../../config/koneksi.php';

// 1. CEK LOGIN ADMIN (PENTING! BIAR GAK SEMBARANGAN HAPUS)
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil data gambar & nama produk untuk log
    $query_cari = mysqli_query($koneksi, "SELECT image, name FROM products WHERE id='$id'");
    $data = mysqli_fetch_assoc($query_cari);
    
    // Simpan nama untuk histori
    $nama_produk = $data['name'] ?? 'Produk ID '.$id;

    // Hapus file gambar fisik
    // PERBAIKAN PATH: Harus mundur 3 kali (../../../)
    $path_gambar = "../../../" . $data['image'];
    if (file_exists($path_gambar)) {
        unlink($path_gambar); 
    }

    // Hapus data dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM products WHERE id='$id'");

    if ($hapus) {
        
        // [HISTORI] CATAT LOG
        if(function_exists('catat_log')) {
            catat_log($koneksi, "Hapus Produk", "Menghapus produk: $nama_produk");
        }

        echo "<script>
                alert('Produk berhasil dihapus!'); 
                window.location = '../pages/produk.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!'); 
                window.location = '../pages/produk.php';
              </script>";
    }
} else {
    header("Location: ../pages/produk.php");
}
?>