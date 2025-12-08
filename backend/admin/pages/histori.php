<?php
session_start();
require_once '../../../config/koneksi.php';

// 1. Cek Login Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// 2. LOGIC BERSIHKAN LOG LAMA
if(isset($_GET['bersihkan'])) {
    mysqli_query($koneksi, "DELETE FROM logs WHERE created_at < NOW() - INTERVAL 30 DAY");
    if(function_exists('catat_log')) catat_log($koneksi, "Bersihkan Log", "Menghapus data histori lama (>30 hari)");
    header("Location: histori.php");
}

// 3. AMBIL DATA LOG
$query_logs = mysqli_query($koneksi, "SELECT * FROM logs ORDER BY created_at DESC LIMIT 100");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Histori Aktivitas - Admin D'Bubuy</title>
    
    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    
    <style>
        .timeline-icon { width: 35px; height: 35px; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 14px; margin-right: 10px; flex-shrink: 0; }
        .bg-tambah { background-color: #1cc88a; } 
        .bg-edit { background-color: #f6c23e; }   
        .bg-hapus { background-color: #e74a3b; }  
        .bg-login { background-color: #4e73df; }  
        .bg-system { background-color: #858796; } 
        .table td { vertical-align: middle; }
        
        /* STYLE MODAL ESTETIK */
        .modal-header { border-bottom: none; background: #4e73df; color: white; }
        .modal-header .close { color: white; opacity: 1; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; background: #f8f9fc; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #e3e6f0; }
        .info-item label { font-size: 0.7rem; font-weight: 800; color: #b7b9cc; text-transform: uppercase; display: block; margin-bottom: 2px; }
        .info-item span { font-weight: 700; color: #5a5c69; font-size: 0.9rem; }
        
        /* Style Rincian Perubahan */
        .change-list { list-style: none; padding: 0; margin: 0; }
        .change-item { background: #fff; border: 1px solid #e3e6f0; padding: 12px; margin-bottom: 10px; border-radius: 6px; border-left: 4px solid #36b9cc; }
        .change-key { font-size: 0.8rem; font-weight: bold; color: #858796; display: block; margin-bottom: 5px; text-transform: uppercase; }
        .val-box { display: flex; align-items: center; flex-wrap: wrap; gap: 10px; font-size: 0.95rem; }
        .val-old { text-decoration: line-through; color: #e74a3b; background: #ffe3e3; padding: 2px 8px; border-radius: 4px; }
        .val-new { color: #1cc88a; background: #e3fff3; padding: 2px 8px; border-radius: 4px; font-weight: bold; }
        .arrow-icon { color: #858796; font-size: 0.8rem; }
        
        .simple-log { font-style: italic; color: #5a5c69; background: #eaecf4; padding: 15px; border-radius: 5px; }
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
                        <h1 class="h3 mb-0 text-gray-800">Histori Aktivitas Admin</h1>
                        <a href="histori.php?bersihkan=1" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" onclick="return confirm('Hapus log lama (>30 hari)?')">
                            <i class="fas fa-trash fa-sm text-white-50"></i> Bersihkan Log Lama
                        </a>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Log Aktivitas Terbaru</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="18%">Waktu</th>
                                            <th width="15%">Admin</th>
                                            <th width="20%">Aktivitas</th>
                                            <th>Ringkasan</th>
                                            <th width="10%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        if(mysqli_num_rows($query_logs) > 0):
                                            while($row = mysqli_fetch_assoc($query_logs)): 
                                                $aksi = strtolower($row['action']);
                                                $icon = "fa-info"; $bg = "bg-system";
                                                if(strpos($aksi, 'tambah') !== false) { $icon="fa-plus"; $bg="bg-tambah"; }
                                                elseif(strpos($aksi, 'edit') !== false || strpos($aksi, 'update') !== false) { $icon="fa-pen"; $bg="bg-edit"; }
                                                elseif(strpos($aksi, 'hapus') !== false || strpos($aksi, 'delete') !== false) { $icon="fa-trash"; $bg="bg-hapus"; }
                                                elseif(strpos($aksi, 'login') !== false) { $icon="fa-sign-in-alt"; $bg="bg-login"; }
                                                elseif(strpos($aksi, 'logout') !== false) { $icon="fa-sign-out-alt"; $bg="bg-login"; }
                                        ?>
                                        <tr>
                                            <td class="small">
                                                <?= date('d M Y', strtotime($row['created_at'])) ?><br>
                                                <span class="text-muted"><?= date('H:i:s', strtotime($row['created_at'])) ?></span>
                                            </td>
                                            <td class="font-weight-bold text-dark"><?= htmlspecialchars($row['username']) ?></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="timeline-icon <?= $bg ?>"> <i class="fas <?= $icon ?>"></i> </div>
                                                    <span class="font-weight-bold text-dark"><?= $row['action'] ?></span>
                                                </div>
                                            </td>
                                            <td class="text-secondary small">
                                                <?= substr(htmlspecialchars($row['details']), 0, 60) ?>...
                                            </td>
                                            <td class="text-center">
                                                <button class="btn btn-info btn-sm btn-circle btn-view-log" 
                                                    data-toggle="modal" 
                                                    data-target="#modalLogDetail"
                                                    data-waktu="<?= date('d F Y, H:i:s', strtotime($row['created_at'])) ?>"
                                                    data-admin="<?= htmlspecialchars($row['username']) ?>"
                                                    data-aksi="<?= htmlspecialchars($row['action']) ?>"
                                                    data-ip="<?= $row['ip_address'] ?>"
                                                    data-detail="<?= htmlspecialchars($row['details']) ?>">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                            <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada aktivitas tercatat.</td></tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <footer class="sticky-footer bg-white">
                <div class="container my-auto"><div class="copyright text-center my-auto"><span>Copyright &copy; D'Bubuy 2025</span></div></div>
            </footer>
        </div>
    </div>

    <div class="modal fade" id="modalLogDetail" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold"><i class="fas fa-history mr-2"></i> Detail Aktivitas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="info-grid">
                        <div class="info-item"><label>Waktu</label><span id="logWaktu">-</span></div>
                        <div class="info-item"><label>Admin</label><span id="logAdmin" class="text-primary">-</span></div>
                        <div class="info-item"><label>Aktivitas</label><span id="logAksi" class="text-dark">-</span></div>
                        <div class="info-item"><label>IP Address</label><span id="logIP" class="text-monospace">-</span></div>
                    </div>
                    
                    <h6 class="font-weight-bold text-gray-800 mb-3 ml-1">Rincian:</h6>
                    <div id="logContentArea">
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

<script>
        $(document).ready(function() {
            $('.btn-view-log').on('click', function() {
                var waktu = $(this).data('waktu');
                var admin = $(this).data('admin');
                var aksi = $(this).data('aksi');
                var ip = $(this).data('ip');
                var rawDetail = $(this).data('detail');

                $('#logWaktu').text(waktu);
                $('#logAdmin').text(admin);
                $('#logAksi').text(aksi);
                $('#logIP').text(ip);

                var htmlContent = '';

                // Cek apakah ini log Edit Produk (Ada kata "Detail:")
                if (rawDetail.includes('Detail:')) {
                    var parts = rawDetail.split('Detail:');
                    var mainMsg = parts[0].trim();
                    var changesStr = parts[1].trim();
                    
                    htmlContent += `<div class="mb-3 text-dark font-weight-bold"><i class="fas fa-tag text-gray-400 mr-2"></i>${mainMsg}</div>`;

                    // LOGIC BARU: Cek pemisahnya Pipa (|) atau Koma (,) untuk support data lama
                    var changes;
                    if (changesStr.includes('|')) {
                        changes = changesStr.split('|'); // Data Baru (Aman)
                    } else {
                        changes = changesStr.split(','); // Data Lama (Mungkin Buggy)
                    }

                    htmlContent += `<ul class="change-list">`;

                    changes.forEach(function(change) {
                        change = change.trim();
                        
                        if (change.includes('->')) {
                            // Pisahkan Key dan Value dengan aman
                            // Cari posisi titik dua (:) pertama
                            var colonIndex = change.indexOf(':');
                            
                            if (colonIndex > -1) {
                                var key = change.substring(0, colonIndex).trim();
                                var valPart = change.substring(colonIndex + 1).trim();
                                var values = valPart.split('->');
                                
                                var oldVal = values[0] ? values[0].trim() : '?';
                                var newVal = values[1] ? values[1].trim() : '?';

                                htmlContent += `
                                    <li class="change-item">
                                        <span class="change-key">${key}</span>
                                        <div class="val-box">
                                            <span class="val-old">${oldVal}</span>
                                            <i class="fas fa-arrow-right arrow-icon"></i>
                                            <span class="val-new">${newVal}</span>
                                        </div>
                                    </li>
                                `;
                            } else {
                                htmlContent += `<li class="change-item">${change}</li>`;
                            }
                        } else {
                            if(change !== "") {
                                htmlContent += `<li class="change-item"><i class="fas fa-check-circle text-success mr-2"></i>${change}</li>`;
                            }
                        }
                    });
                    htmlContent += `</ul>`;

                } else {
                    htmlContent = `<div class="simple-log"><i class="fas fa-sticky-note mr-2"></i>${rawDetail}</div>`;
                }

                $('#logContentArea').html(htmlContent);
            });
        });
    </script>
</body>
</html>