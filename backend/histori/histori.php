<?php
session_start();

// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");

if (!$koneksi) {
    die("Gagal Konek: " . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin D'Bubuy - Histori</title>

    <link href="../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../assets/css/admin.css" rel="stylesheet"> 
</head>

<body id="page-top">
    <div id="wrapper">
        
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="../admin/index_admin.php">
                <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-utensils"></i></div>
                <div class="sidebar-brand-text mx-3">Admin D'Bubuy</div>
            </a>
            <hr class="sidebar-divider my-0">
            
            <li class="nav-item">
                <a class="nav-link" href="../admin/index_admin.php"><i class="fas fa-fw fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Menu Toko</div>
            
            <li class="nav-item">
                <a class="nav-link" href="../produk/produk.php"><i class="fas fa-fw fa-box"></i> <span>Kelola Produk</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="../pesanan.php"><i class="fas fa-fw fa-shopping-cart"></i> <span>Pesanan</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="../customer.php"><i class="fas fa-fw fa-users"></i> <span>Customer</span></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="../media.php"><i class="fas fa-fw fa-newspaper"></i> <span>Liputan Media</span></a>
            </li>

            <li class="nav-item active">
                <a class="nav-link" href="histori.php">
                    <i class="fas fa-fw fa-history"></i> 
                    <span>Histori</span>
                </a>
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
                        <h1 class="h3 mb-0 text-gray-800">Laporan Histori Aktivitas</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Data Log Histori</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Tanggal & Waktu</th>
                                            <th>Aktivitas</th>
                                            <th>User / Pelaku</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="5" class="text-center py-5">
                                                <img src="../../assets/template/sbadmin2/img/undraw_posting_photo.svg" style="width: 150px; opacity: 0.5;" class="mb-3">
                                                <p class="text-gray-500 mb-0">Belum ada data histori aktivitas.</p>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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

    <script src="../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

</body>
</html>