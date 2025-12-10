<?php
session_start();
// Gunakan include biar fungsi catat_log terbaca
require_once '../../../config/koneksi.php';

// 1. Cek Login Admin (WAJIB)
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// 2. Ambil ID
if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit;
}
$id = $_GET['id'];

// 3. Ambil Data Lama
$query = mysqli_query($koneksi, "SELECT * FROM products WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "<script>alert('Data tidak ditemukan!'); window.location='produk.php';</script>";
    exit;
}

// 4. PROSES UPDATE
if (isset($_POST['btn_update'])) {
    
    $nama       = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $harga      = $_POST['harga'];
    $estimasi   = $_POST['estimasi'];
    $deskripsi  = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $foto_lama  = $_POST['foto_lama'];

    $foto_nama = $_FILES['foto']['name'];
    $foto_tmp  = $_FILES['foto']['tmp_name'];
    
    if ($foto_nama != "") {
        // Ganti Foto
        $nama_baru = time() . "_" . $foto_nama;
        $upload_dir = "../../../assets/img/";
        
        if (move_uploaded_file($foto_tmp, $upload_dir . $nama_baru)) {
            // Hapus foto lama
            if (file_exists("../../../" . $foto_lama) && $foto_lama != "") {
                unlink("../../../" . $foto_lama);
            }
            
            $foto_db = "assets/img/" . $nama_baru;
            $q_update = "UPDATE products SET name='$nama', price='$harga', estimation='$estimasi', description='$deskripsi', image='$foto_db' WHERE id='$id'";
        }
    } else {
        // Tidak Ganti Foto
        $q_update = "UPDATE products SET name='$nama', price='$harga', estimation='$estimasi', description='$deskripsi' WHERE id='$id'";
    }

    if (mysqli_query($koneksi, $q_update)) {
        
        // [HISTORI] CATAT LOG
        if(function_exists('catat_log')) {
            catat_log($koneksi, "Edit Produk", "Mengupdate data produk: $nama");
        }
        
        echo "<script>alert('Produk Berhasil Diperbarui!'); window.location='produk.php';</script>";
    } else {
        echo "<script>alert('Gagal Update: " . mysqli_error($koneksi) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk - Admin D'Bubuy</title>

    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <style>
        .img-preview-container {
            width: 100%; height: 250px;
            border: 2px dashed #ddd; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            overflow: hidden; background-color: #f8f9fc; position: relative;
        }
        .img-preview { max-width: 100%; max-height: 100%; object-fit: contain; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">
        <?php include 'partials/sidebar.php'; ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include 'partials/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Edit Produk</h1>
                        <a href="produk.php" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">
                            <i class="fas fa-arrow-left fa-sm text-white-50"></i> Kembali
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Edit Data Menu</h6>
                        </div>
                        <div class="card-body">
                            <form method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="foto_lama" value="<?= $data['image'] ?>">
                                <div class="row">
                                    <div class="col-lg-7">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark">Nama Produk</label>
                                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data['name']) ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold text-dark">Harga (Rp)</label>
                                                <input type="number" name="harga" class="form-control" value="<?= $data['price'] ?>" required>
                                            </div>
                                            <div class="form-group col-md-6">
                                                <label class="font-weight-bold text-dark">Estimasi Waktu</label>
                                                <input type="text" name="estimasi" class="form-control" value="<?= htmlspecialchars($data['estimation']) ?>" required>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark">Deskripsi</label>
                                            <textarea name="deskripsi" class="form-control" rows="5" required><?= htmlspecialchars($data['description']) ?></textarea>
                                        </div>
                                    </div>
                                    <div class="col-lg-5">
                                        <div class="form-group">
                                            <label class="font-weight-bold text-dark">Foto Makanan</label>
                                            <div class="img-preview-container mb-3">
                                                <img id="imgPreview" class="img-preview" src="../../../<?= $data['image'] ?>" alt="Foto Produk">
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" name="foto" class="custom-file-input" id="fotoInput" accept="image/*" onchange="previewImage()">
                                                <label class="custom-file-label" for="fotoInput">Ganti Foto (Opsional)...</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="text-right">
                                    <button type="submit" name="btn_update" class="btn btn-primary btn-icon-split px-4 py-2">
                                        <span class="icon text-white-50"><i class="fas fa-save"></i></span>
                                        <span class="text">Simpan Perubahan</span>
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
            const label = document.querySelector('.custom-file-label');
            if(foto.files && foto.files[0]){
                label.textContent = foto.files[0].name;
                const oFReader = new FileReader();
                oFReader.readAsDataURL(foto.files[0]);
                oFReader.onload = function(oFREvent) { imgPreview.src = oFREvent.target.result; }
            }
        }
    </script>
</body>
</html>