<?php
session_start();
require_once '../../../config/koneksi.php';

// 1. Cek Akses Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// 2. Logic Toggle Banned (Ubah Status Blokir)
// 2. Logic Toggle Banned (Ubah Status Blokir)
if (isset($_GET['id_user']) && isset($_GET['set_ban'])) {
    $id_user = $_GET['id_user'];
    $status_baru = $_GET['set_ban']; // 1 untuk banned, 0 untuk unban

    // Update status
    $q_update = "UPDATE users SET is_banned = '$status_baru' WHERE id = '$id_user' AND kategori = 'customer'";
    
    if (mysqli_query($koneksi, $q_update)) {
        
        // [BARU] CATAT LOG
        $aksi_log = ($status_baru == 1) ? "Banned User" : "Unbanned User";
        catat_log($koneksi, $aksi_log, "Mengubah status user ID: $id_user");

        $pesan = ($status_baru == 1) ? "User berhasil dibanned (diblokir)." : "User berhasil diaktifkan kembali.";
        echo "<script>alert('$pesan'); window.location='customer.php';</script>";
    }
}

// 3. Konfigurasi Pagination & Pencarian
$batas = 10;
$halaman = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$previous = $halaman - 1;
$next = $halaman + 1;

// Filter Pencarian
$search_keyword = "";
$where_clause = "WHERE kategori = 'customer'";

if (isset($_GET['cari'])) {
    $search_keyword = $_GET['cari'];
    $where_clause .= " AND (nama_lengkap LIKE '%$search_keyword%' OR no_whatsapp LIKE '%$search_keyword%' OR username LIKE '%$search_keyword%')";
}

// Hitung Total Data
$data = mysqli_query($koneksi, "SELECT * FROM users $where_clause");
$jumlah_data = mysqli_num_rows($data);
$total_halaman = ceil($jumlah_data / $batas);

// Ambil Data Customer
$query_customer = mysqli_query($koneksi, "SELECT * FROM users $where_clause ORDER BY created_at DESC LIMIT $halaman_awal, $batas");
$nomor = $halaman_awal + 1;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Customer - Admin D'Bubuy</title>
    
    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .avatar-sm {
            width: 35px; height: 35px; background-color: #e9ecef; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: bold; color: #555; font-size: 14px;
        }
        
        /* Style untuk baris yang dibanned */
        .row-banned {
            background-color: #ffeeee;
            color: #888;
        }
        .row-banned td {
            text-decoration: line-through; /* Efek coret */
            color: #999;
        }
        .row-banned td.action-cell {
            text-decoration: none; /* Jangan coret tombol aksi */
        }
        
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
                        <h1 class="h3 mb-0 text-gray-800">Data Customer</h1>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                            <h6 class="m-0 font-weight-bold text-primary">List Pelanggan Terdaftar (<?= $jumlah_data ?>)</h6>
                            
                            <form action="" method="GET" class="form-inline">
                                <div class="input-group input-group-sm">
                                    <input type="text" name="cari" class="form-control bg-light border-0 small" placeholder="Cari nama / WA..." value="<?= $search_keyword ?>">
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search fa-sm"></i>
                                        </button>
                                        <?php if($search_keyword): ?>
                                            <a href="customer.php" class="btn btn-secondary">
                                                <i class="fas fa-times fa-sm"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th>Status</th>
                                            <th>Nama Lengkap</th>
                                            <th>No. WhatsApp</th>
                                            <th>Username</th>
                                            <th>Bergabung</th>
                                            <th width="15%" class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while($row = mysqli_fetch_assoc($query_customer)): 
                                            // Cek status banned
                                            $is_banned = isset($row['is_banned']) && $row['is_banned'] == 1;
                                            $row_class = $is_banned ? 'row-banned' : '';
                                        ?>
                                        <tr class="<?= $row_class ?>">
                                            <td><?= $nomor++ ?></td>
                                            <td>
                                                <?php if($is_banned): ?>
                                                    <span class="badge badge-danger">BANNED</span>
                                                <?php else: ?>
                                                    <span class="badge badge-success">AKTIF</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm mr-2">
                                                        <?= strtoupper(substr($row['nama_lengkap'], 0, 1)) ?>
                                                    </div>
                                                    <?= htmlspecialchars($row['nama_lengkap']) ?>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="https://wa.me/<?= preg_replace('/^0/', '62', $row['no_whatsapp']) ?>" target="_blank" class="text-success font-weight-bold">
                                                    <i class="fab fa-whatsapp"></i> <?= $row['no_whatsapp'] ?>
                                                </a>
                                            </td>
                                            <td><?= $row['username'] ?? '-' ?></td>
                                            <td><?= date('d M Y', strtotime($row['created_at'])) ?></td>
                                            <td class="action-cell text-center">
                                                <?php if($is_banned): ?>
                                                    <a href="customer.php?id_user=<?= $row['id'] ?>&set_ban=0" class="btn btn-success btn-sm btn-icon-split" onclick="return confirm('Aktifkan kembali user ini?')">
                                                        <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                                                        <span class="text">Unban</span>
                                                    </a>
                                                <?php else: ?>
                                                    <a href="customer.php?id_user=<?= $row['id'] ?>&set_ban=1" class="btn btn-danger btn-sm btn-icon-split" onclick="return confirm('Blokir user ini? User tidak akan bisa login.')">
                                                        <span class="icon text-white-50"><i class="fas fa-ban"></i></span>
                                                        <span class="text">Ban</span>
                                                    </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>

                                        <?php if(mysqli_num_rows($query_customer) == 0): ?>
                                            <tr>
                                                <td colspan="7" class="text-center py-4 text-muted">Tidak ada data customer ditemukan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if($total_halaman > 1): ?>
                            <nav class="mt-4">
                                <ul class="pagination justify-content-end">
                                    <li class="page-item <?= ($halaman <= 1) ? 'disabled' : '' ?>">
                                        <a class="page-link" <?php if($halaman > 1){ echo "href='?hal=$previous&cari=$search_keyword'"; } ?>>Previous</a>
                                    </li>
                                    <?php for($x = 1; $x <= $total_halaman; $x++): ?>
                                        <li class="page-item <?= ($halaman == $x) ? 'active' : '' ?>">
                                            <a class="page-link" href="?hal=<?= $x ?>&cari=<?= $search_keyword ?>"><?= $x ?></a>
                                        </li>
                                    <?php endfor; ?>
                                    <li class="page-item <?= ($halaman >= $total_halaman) ? 'disabled' : '' ?>">
                                        <a class="page-link" <?php if($halaman < $total_halaman) { echo "href='?hal=$next&cari=$search_keyword'"; } ?>>Next</a>
                                    </li>
                                </ul>
                            </nav>
                            <?php endif; ?>

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