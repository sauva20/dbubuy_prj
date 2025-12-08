<?php
session_start();

// 1. KONEKSI DB
// Sesuaikan path koneksi jika berbeda (misal: ../../../config/koneksi.php)
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");
if (!$koneksi) { die("Koneksi Gagal: " . mysqli_connect_error()); }

// Cek Login Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Produk - Admin D'Bubuy</title>

    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <style>
        .img-product-list { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; border: 1px solid #eee; }
        .table td { vertical-align: middle; }
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Produk</h1>
                        <a href="tambah_produk.php" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Produk Baru
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Semua Menu</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="15%">Gambar</th>
                                            <th>Nama Produk</th>
                                            <th>Harga</th>
                                            <th>Estimasi</th>
                                            <th width="15%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $query = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
                                        
                                        if(mysqli_num_rows($query) > 0){
                                            while($data = mysqli_fetch_assoc($query)){
                                                // Path gambar (Sesuaikan dengan struktur folder penyimpanan gambar)
                                                // Asumsi gambar disimpan di folder assets/img/ di root project
                                                $gambar = "../../../" . $data['image'];
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++; ?></td>
                                            <td class="text-center">
                                                <img src="<?= $gambar; ?>" class="img-product-list" alt="Produk">
                                            </td>
                                            <td class="font-weight-bold text-dark">
                                                <?= $data['name']; ?>
                                                <br>
                                                <small class="text-muted"><?= substr($data['description'], 0, 50); ?>...</small>
                                            </td>
                                            <td>Rp <?= number_format($data['price'], 0, ',', '.'); ?></td>
                                            <td>
                                                <span class="badge badge-info"><?= $data['estimation']; ?></span>
                                            </td>
                                            <td class="text-center">
                                                <a href="../action/edit_produk.php?id=<?= $data['id']; ?>" class="btn btn-warning btn-sm btn-circle" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                
                                                <a href="../action/hapus_produk.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('Yakin ingin menghapus produk ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada produk. Silakan tambah produk baru.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; D'Bubuy Ma'Atik 2025</span>
                    </div>
                </div>
            </footer>

        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

</body>
</html>