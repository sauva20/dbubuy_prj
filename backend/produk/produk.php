<?php
session_start();

// 1. KONEKSI DB
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");
if (!$koneksi) { die("Koneksi Gagal: " . mysqli_connect_error()); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Produk - Admin D'Bubuy</title>

    <link href="../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/css/admin.css" rel="stylesheet"> <style>
        .img-product-list { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
    </style>
</head>

<body id="page-top">
    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_admin.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-utensils"></i></div>
                <div class="sidebar-brand-text mx-3">Admin D'Bubuy</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="index_admin.php"><i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Toko</div>
            <li class="nav-item active">
                <a class="nav-link" href="produk.php"><i class="fas fa-fw fa-box"></i> <span>Kelola Produk</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php"><i class="fas fa-fw fa-shopping-cart"></i> <span>Pesanan</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="customer.php"><i class="fas fa-fw fa-users"></i> <span>Customer</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="media.php"><i class="fas fa-fw fa-newspaper"></i> <span>Liputan Media</span></a>
            </li>
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
                                <img class="img-profile rounded-circle" src="../../assets/template/sbadmin2/img/undraw_profile.svg">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in">
                                <a class="dropdown-item" href="../../action/logout.php"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i> Logout</a>
                            </div>
                        </li>
                    </ul>
                </nav>

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
                                        // Query Ambil SEMUA Produk
                                        $no = 1;
                                        $query = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC");
                                        
                                        if(mysqli_num_rows($query) > 0){
                                            while($data = mysqli_fetch_assoc($query)){
                                                // Path gambar
                                                $gambar = "../../" . $data['image'];
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
                                                <a href="edit_produk.php?id=<?= $data['id']; ?>" class="btn btn-warning btn-sm btn-circle" title="Edit">
                                                    <i class="fas fa-pen"></i>
                                                </a>
                                                
                                                <a href="hapus_produk.php?id=<?= $data['id']; ?>" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('Yakin ingin menghapus produk ini?')" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php 
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-5'>Belum ada produk. Silakan tambah produk baru.</td></tr>";
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

    <script src="../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

</body>
</html>