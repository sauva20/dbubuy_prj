<?php
session_start();

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");
if (!$koneksi) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// Ambil ID dari URL
if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit;
}
$id = $_GET['id'];

// Ambil Data Produk Lama
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$data  = mysqli_fetch_assoc($query);

// LOGIKA UPDATE
if (isset($_POST['update'])) {
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga      = $_POST['harga'];
    $estimasi   = $_POST['estimasi'];
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $foto_lama  = $_POST['foto_lama'];

    // Cek apakah user upload foto baru?
    if ($_FILES['foto']['name'] != "") {
        $nama_file   = $_FILES['foto']['name'];
        $tmp_file    = $_FILES['foto']['tmp_name'];
        $nama_baru   = time() . "_" . $nama_file; // Rename biar unik
        $path_upload = "../../assets/img/" . $nama_baru; // Path fisik
        $path_db     = "assets/img/" . $nama_baru;     // Path database

        // Upload foto baru
        if (move_uploaded_file($tmp_file, $path_upload)) {
            // Hapus foto lama dari folder (Opsional, biar hemat storage)
            if (file_exists("../../" . $foto_lama)) {
                unlink("../../" . $foto_lama);
            }
            $foto_final = $path_db;
        } else {
            $foto_final = $foto_lama; // Jika gagal upload, pakai yang lama
        }
    } else {
        // Jika tidak ganti foto
        $foto_final = $foto_lama;
    }

    // Query Update
    $sql = "UPDATE products SET 
            name = '$nama', price = '$harga', estimation = '$estimasi', 
            description = '$deskripsi', image = '$foto_final' 
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $sql)) {
        echo "<script>alert('Produk Berhasil Diupdate!'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal Update!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk - Admin D'Bubuy</title>
    <link href="../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/css/admin.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_admin.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-utensils"></i></div>
                <div class="sidebar-brand-text mx-3">Admin D'Bubuy</div>
            </a>
            <li class="nav-item"><a class="nav-link" href="index_admin.php"><i class="fas fa-fw fa-tachometer-alt"></i> Dashboard</a></li>
            <li class="nav-item active"><a class="nav-link" href="produk.php"><i class="fas fa-fw fa-box"></i> Kelola Produk</a></li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow"></nav>

                <div class="container-fluid">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Edit Produk</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label>Nama Produk</label>
                                    <input type="text" name="nama" class="form-control" value="<?= $data['name']; ?>" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Harga (Rp)</label>
                                        <input type="number" name="harga" class="form-control" value="<?= $data['price']; ?>" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Estimasi Waktu</label>
                                        <input type="text" name="estimasi" class="form-control" value="<?= $data['estimation']; ?>" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Deskripsi</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" required><?= $data['description']; ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label>Gambar Saat Ini</label><br>
                                    <img src="../../<?= $data['image']; ?>" style="width:100px; border-radius:10px; margin-bottom:10px;">
                                    
                                    <input type="hidden" name="foto_lama" value="<?= $data['image']; ?>">
                                    
                                    <br>
                                    <label>Ganti Gambar (Opsional)</label>
                                    <input type="file" name="foto" class="form-control-file">
                                </div>

                                <hr>
                                <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                                <a href="produk.php" class="btn btn-secondary">Batal</a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>
</body>
</html>