<?php
session_start();

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil nama gambar (untuk dihapus dari folder)
    $query_cari = mysqli_query($koneksi, "SELECT image FROM products WHERE id='$id'");
    $data = mysqli_fetch_assoc($query_cari);
    
    // Hapus file gambar 
    $path_gambar = "../../" . $data['image'];
    if (file_exists($path_gambar)) {
        unlink($path_gambar); 
    }

    // Hapus data dari database
    $hapus = mysqli_query($koneksi, "DELETE FROM products WHERE id='$id'");

    if ($hapus) {
        echo "<script>
                alert('Produk berhasil dihapus!'); 
                window.location = 'produk.php';
              </script>";
    } else {
        echo "<script>
                alert('Gagal menghapus data!'); 
                window.location = 'produk.php';
              </script>";
    }
} else {
    header("Location: produk.php");
}
?>