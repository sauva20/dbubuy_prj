<?php
session_start();
// Koneksi Database
$koneksi = mysqli_connect("localhost", "root", "", "si_gudang");

if (!$koneksi) { die("Gagal Konek: " . mysqli_connect_error()); }

// --- LOGIC HITUNG DATA (Sama seperti sebelumnya) ---
// koneksi sql bukan dalam koneksi.php
$total_produk = 0;
$q_prod = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM products");
if ($q_prod) { $d = mysqli_fetch_assoc($q_prod); $total_produk = $d['total']; }

$total_customer = 0;
$q_cust = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM users WHERE kategori='customer'");
if ($q_cust) { $d = mysqli_fetch_assoc($q_cust); $total_customer = $d['total']; }

$total_media = 0;
$q_media = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM liputan");
if ($q_media) { $d = mysqli_fetch_assoc($q_media); $total_media = $d['total']; }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Admin D'Bubuy - Dashboard</title>

    <link href="/assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="/assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="/assets/css/admin.css" rel="stylesheet"> 
</head>

<body id="page-top">
    <div id="wrapper"> <!--useles-->
        
        <?php include 'partials/sidebar.php'; ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                
                <?php include 'partials/topbar.php'; ?>

                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Dashboard Ringkasan</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pendapatan</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rp 4.500.000</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Produk</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_produk; ?> Item</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-utensils fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-info shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Artikel Media</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_media; ?> Berita</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-newspaper fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-3 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Customer</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?= $total_customer; ?> User</div>
                                        </div>
                                        <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Produk Terbaru</h6>
                                    <a href="../produk/tambah_produk.php" class="btn btn-sm btn-success"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead><tr><th>Foto</th><th>Nama</th><th>Harga</th><th>Aksi</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $q_p = mysqli_query($koneksi, "SELECT * FROM products ORDER BY id DESC LIMIT 3");
                                                while($p = mysqli_fetch_assoc($q_p)){
                                                    $img = "../../" . $p['image']; 
                                                ?>
                                                <tr>
                                                    <td><img src="<?= $img; ?>" class="img-thumb" style="width:50px;"></td>
                                                    <td><?= $p['name']; ?></td>
                                                    <td>Rp <?= number_format($p['price'],0,',','.'); ?></td>
                                                    <td>
                                                        <a href="../produk/edit_produk.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm btn-circle"><i class="fas fa-pen"></i></a>
                                                    </td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                                    <h6 class="m-0 font-weight-bold text-primary">Liputan Media</h6>
                                    <a href="../tambah_media.php" class="btn btn-sm btn-primary"><i class="fas fa-plus"></i> Tambah</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead><tr><th>Logo</th><th>Media</th></tr></thead>
                                            <tbody>
                                                <?php
                                                $q_m = mysqli_query($koneksi, "SELECT * FROM liputan ORDER BY id DESC LIMIT 3");
                                                while($m = mysqli_fetch_assoc($q_m)){
                                                    $img_m = "../../" . $m['image'];
                                                ?>
                                                <tr>
                                                    <td class="text-center"><img src="<?= $img_m; ?>" class="img-media" style="height:30px;"></td>
                                                    <td><?= $m['media_name']; ?></td>
                                                </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
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