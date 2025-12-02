<?php
session_start();

// 1. KONEKSI
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");
if (!$koneksi) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// 2. PROSES SIMPAN DATA (Saat tombol ditekan)
if (isset($_POST['simpan'])) {
    
    // Tangkap data text
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga      = $_POST['harga'];
    $estimasi   = $_POST['estimasi'];
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    // --- LOGIKA UPLOAD GAMBAR ---
    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    
    if($foto_nama != "") {
        // Buat nama unik biar gak bentrok (contoh: 170988_ayam.jpg)
        $nama_baru = time() . "_" . $foto_nama;
        
        // Folder Tujuan (Keluar dari backend/admin -> Masuk ke assets/img)
        $upload_dir = "../../../assets/img/";
        
        // Pindahkan file
        if(move_uploaded_file($foto_tmp, $upload_dir . $nama_baru)) {
            
            // Simpan path relatif untuk database
            $foto_db = "assets/img/" . $nama_baru;

            // INSERT KE DATABASE
            $query = "INSERT INTO products (name, description, price, estimation, image) 
                      VALUES ('$nama', '$deskripsi', '$harga', '$estimasi', '$foto_db')";
            
            if(mysqli_query($koneksi, $query)) {
                echo "<script>alert('Produk Berhasil Ditambahkan!'); window.location='produk.php';</script>";
            } else {
                echo "<script>alert('Gagal Simpan Database: " . mysqli_error($koneksi) . "');</script>";
            }

        } else {
            echo "<script>alert('Gagal Upload Gambar! Cek folder assets/img');</script>";
        }
    } else {
        echo "<script>alert('Harap pilih gambar produk!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk - Admin D'Bubuy</title>

    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet">
</head>

<body id="page-top">
    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_admin.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-utensils"></i></div>
                <div class="sidebar-brand-text mx-3">Admin D'Bubuy</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item"><a class="nav-link" href="index_admin.php"><i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span></a></li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Toko</div>
            <li class="nav-item active"><a class="nav-link" href="produk.php"><i class="fas fa-fw fa-box"></i> <span>Kelola Produk</span></a></li>
            <li class="nav-item"><a class="nav-link" href="pesanan.php"><i class="fas fa-fw fa-shopping-cart"></i> <span>Pesanan</span></a></li>
            <li class="nav-item"><a class="nav-link" href="customer.php"><i class="fas fa-fw fa-users"></i> <span>Customer</span></a></li>
            <li class="nav-item"><a class="nav-link" href="media.php"><i class="fas fa-fw fa-newspaper"></i> <span>Liputan Media</span></a></li>
            <hr class="sidebar-divider d-none d-md-block">
            <div class="text-center d-none d-md-inline"><button class="rounded-circle border-0" id="sidebarToggle"></button></div>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"><i class="fa fa-bars"></i></button>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Administrator</span>
                                <img class="img-profile rounded-circle" src="../../../assets/template/sbadmin2/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="../../../action/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>

                <div class="container-fluid">
                    
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Menu Baru</h6>
                        </div>
                        <div class="card-body">
                            
                            <form method="POST" enctype="multipart/form-data">
                                
                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Nama Produk / Menu</label>
                                    <input type="text" name="nama" class="form-control" placeholder="Contoh: Bubuy Ayam Kampung" required>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Harga (Rp)</label>
                                        <input type="number" name="harga" class="form-control" placeholder="Contoh: 150000" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="font-weight-bold text-dark">Estimasi Waktu (Durasi Masak)</label>
                                        <input type="text" name="estimasi" class="form-control" placeholder="Contoh: 6-8 Jam / Ready Stock" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Deskripsi Singkat</label>
                                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Jelaskan rasa dan kelebihan menu ini..." required></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="font-weight-bold text-dark">Upload Foto Makanan</label>
                                    <input type="file" name="foto" class="form-control-file" required>
                                    <small class="text-muted">Format: JPG, PNG, JPEG.</small>
                                </div>

                                <hr>
                                <button type="submit" name="simpan" class="btn btn-success btn-icon-split">
                                    <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                    <span class="text">Simpan Produk</span>
                                </button>
                                <a href="produk.php" class="btn btn-secondary btn-icon-split ml-2">
                                    <span class="text">Batal</span>
                                </a>

                            </form>

                        </div>
                    </div>

                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto"><span>Copyright &copy; D'Bubuy 2025</span></div>
                </div>
            </footer>
        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>
</body>
</html>