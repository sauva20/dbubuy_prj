<?php
session_start();
// Include file koneksi yang berisi fungsi catat_log
require_once '../../../config/koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// PROSES SIMPAN DATA
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
        $nama_baru = time() . "_" . $foto_nama;
        // Path fisik file
        $upload_dir = "../../../assets/img/";
        
        if(move_uploaded_file($foto_tmp, $upload_dir . $nama_baru)) {
            
            // Path database
            $foto_db = "assets/img/" . $nama_baru;

            $query = "INSERT INTO products (name, description, price, estimation, image) 
                      VALUES ('$nama', '$deskripsi', '$harga', '$estimasi', '$foto_db')";
            
            if(mysqli_query($koneksi, $query)) {
                
                // [BARU] CATAT LOG
                if(function_exists('catat_log')) {
                    catat_log($koneksi, "Tambah Produk", "Menambahkan produk baru: $nama");
                }

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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Produk - Admin D'Bubuy</title>

    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <style>
        .img-preview-container {
            width: 100%; height: 250px;
            border: 2px dashed #ddd; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; background-color: #f8f9fc;
            position: relative;
        }
        .img-preview { max-width: 100%; max-height: 100%; display: none; object-fit: contain; }
        .preview-text { color: #aaa; font-size: 14px; position: absolute; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">

        <?php include '../../admin/partials/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                
                <?php include '../../admin/partials/topbar.php'; ?>

                <div class="container-fluid">
                    
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Produk Baru</h1>
                        <a href="produk.php" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Form Input Menu</h6>
                        </div>
                        <div class="card-body">
                            
                            <form method="POST" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-lg-7">
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
                                                <label class="font-weight-bold text-dark">Estimasi Waktu</label>
                                                <input type="text" name="estimasi" class="form-control" placeholder="Contoh: 6-8 Jam" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark">Deskripsi Singkat</label>
                                            <textarea name="deskripsi" class="form-control" rows="4" placeholder="Jelaskan rasa dan kelebihan menu ini..." required></textarea>
                                        </div>
                                    </div>

                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark">Foto Makanan</label>
                                            <div class="img-preview-container mb-3">
                                                <span class="preview-text">Preview Gambar</span>
                                                <img id="imgPreview" class="img-preview" src="#" alt="Preview Foto">
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="foto" class="custom-file-input" id="fotoInput" accept="image/*" onchange="previewImage()" required>
                                                <label class="custom-file-label" for="fotoInput">Pilih File...</label>
                                            </div>
                                            <small class="text-muted mt-2 d-block">Format: JPG, PNG. Maksimal 2MB.</small>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-right">
                                    <button type="submit" name="simpan" class="btn btn-success btn-icon-split px-4 py-2">
                                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                        <span class="text">Simpan Produk</span>
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
            
            <footer class="sticky-footer bg-white">
                <div class="container my-auto"><div class="copyright text-center my-auto"><span>Copyright &copy; D'Bubuy 2025</span></div></div>
            </footer>
        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

    <script>
        function previewImage() {
            const foto = document.querySelector('#fotoInput');
            const imgPreview = document.querySelector('#imgPreview');
            const previewText = document.querySelector('.preview-text');
            const label = document.querySelector('.custom-file-label');

            if(foto.files && foto.files[0]){
                label.textContent = foto.files[0].name;
                const oFReader = new FileReader();
                oFReader.readAsDataURL(foto.files[0]);
                oFReader.onload = function(oFREvent) {
                    imgPreview.src = oFREvent.target.result;
                    imgPreview.style.display = 'block';
                    previewText.style.display = 'none';
                }
            }
        }
    </script>
</body>
</html>