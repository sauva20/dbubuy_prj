<?php
session_start();
require_once '../../../config/koneksi.php';

// 1. Cek Akses Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// 2. Logic Update Status Pesanan
if (isset($_POST['btn_update_status'])) {
    $order_id = $_POST['order_id'];
    $status_baru = $_POST['status'];
    
    $query_update = "UPDATE orders SET status = '$status_baru' WHERE id = '$order_id'";
    if (mysqli_query($koneksi, $query_update)) {
        if(function_exists('catat_log')) catat_log($koneksi, "Update Status", "Mengubah status Order #$order_id menjadi $status_baru");
        echo "<script>alert('Status pesanan berhasil diperbarui!'); window.location='pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal update status.');</script>";
    }
}

// --- 3. FILTER TANGGAL (DEFAULT: BULAN INI) ---
$start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01'); // Tanggal 1 bulan ini
$end_date   = isset($_GET['end_date'])   ? $_GET['end_date']   : date('Y-m-d');  // Hari ini

// Query Tambahan untuk Filter
$where_date = "AND created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";
$where_date_order = "WHERE created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'";

// --- 4. DATA UNTUK CHART & TABEL ---

// A. Tabel Pesanan (Sesuai Filter Tanggal)
$query_orders = mysqli_query($koneksi, "SELECT * FROM orders $where_date_order ORDER BY created_at DESC");

// B. Grafik Tren Pendapatan (Harian dalam Range Tanggal)
$chart_trend_label = [];
$chart_trend_data = [];

$q_trend = mysqli_query($koneksi, "
    SELECT DATE(created_at) as tgl, SUM(total_amount) as omset 
    FROM orders 
    WHERE status IN ('success', 'settlement', 'capture')
    $where_date
    GROUP BY DATE(created_at)
    ORDER BY tgl ASC
");

while($r = mysqli_fetch_assoc($q_trend)) {
    $chart_trend_label[] = date('d M', strtotime($r['tgl']));
    $chart_trend_data[] = $r['omset'];
}

// C. Grafik Status Pesanan
$chart_status_label = [];
$chart_status_data = [];
$q_stat = mysqli_query($koneksi, "SELECT status, COUNT(*) as total FROM orders $where_date_order GROUP BY status");
while($r = mysqli_fetch_assoc($q_stat)) {
    $chart_status_label[] = strtoupper($r['status']);
    $chart_status_data[] = $r['total'];
}

// D. Grafik Produk Terlaris (Top Menu)
// Kita JOIN ke tabel orders untuk cek status pembayaran dan filter tanggal
$chart_prod_label = [];
$chart_prod_data = [];
$q_top = mysqli_query($koneksi, "
    SELECT oi.product_name, SUM(oi.quantity) as qty 
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    WHERE o.status IN ('success', 'settlement', 'capture')
    AND o.created_at BETWEEN '$start_date 00:00:00' AND '$end_date 23:59:59'
    GROUP BY oi.product_name 
    ORDER BY qty DESC
");

while($r = mysqli_fetch_assoc($q_top)) {
    $chart_prod_label[] = $r['product_name']; // Tampilkan nama full
    $chart_prod_data[] = $r['qty'];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Analitik Pesanan - Admin D'Bubuy</title>
    
    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .table th { vertical-align: middle; white-space: nowrap; }
        .table td { vertical-align: middle; }
        
        .status-badge { padding: 5px 10px; border-radius: 15px; font-size: 11px; font-weight: bold; color: white; }
        .bg-pending { background-color: #f6c23e; }
        .bg-proses { background-color: #36b9cc; }
        .bg-success { background-color: #1cc88a; }
        .bg-failed { background-color: #e74a3b; }

        .chart-card { border-radius: 10px; border: none; box-shadow: 0 0 15px rgba(0,0,0,0.05); }
        .chart-header { background: #fff; border-bottom: 1px solid #f0f0f0; border-radius: 10px 10px 0 0; font-weight: bold; color: #4e73df; }
        
        /* Style untuk Form Filter */
        .filter-box { background: #fff; padding: 15px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); margin-bottom: 20px; }
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
                        <h1 class="h3 mb-0 text-gray-800">Analitik Penjualan</h1>
                    </div>

                    <div class="filter-box border-left-primary">
                        <form method="GET" class="form-inline">
                            <label class="mr-2 font-weight-bold text-gray-700">Periode:</label>
                            <input type="date" name="start_date" class="form-control form-control-sm mr-2" value="<?= $start_date ?>">
                            <span class="mr-2">s/d</span>
                            <input type="date" name="end_date" class="form-control form-control-sm mr-2" value="<?= $end_date ?>">
                            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter"></i> Terapkan</button>
                            <a href="pesanan.php" class="btn btn-secondary btn-sm ml-2"><i class="fas fa-sync"></i> Reset</a>
                        </form>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 col-lg-7">
                            <div class="card chart-card shadow mb-4">
                                <div class="card-header chart-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Grafik Pendapatan (<?= date('d M', strtotime($start_date)) ?> - <?= date('d M', strtotime($end_date)) ?>)</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                    <?php if(empty($chart_trend_data)): ?>
                                        <div class="text-center mt-3 text-muted small">Tidak ada transaksi sukses pada periode ini.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-lg-5">
                            <div class="card chart-card shadow mb-4">
                                <div class="card-header chart-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Status Pesanan</h6>
                                </div>
                                <div class="card-body">
                                    <div class="chart-pie pt-4 pb-2">
                                        <canvas id="myPieChart"></canvas>
                                    </div>
                                    <div class="mt-4 text-center small text-muted">
                                        Rasio pesanan Sukses, Pending, dan Batal.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                         <div class="col-12 mb-4">
                            <div class="card chart-card shadow">
                                <div class="card-header chart-header py-3">
                                    <h6 class="m-0 font-weight-bold text-success">Menu Terlaris (Terjual)</h6>
                                </div>
                                <div class="card-body">
                                    <div style="height: 300px;">
                                        <canvas id="myBarChart"></canvas>
                                    </div>
                                    <?php if(empty($chart_prod_data)): ?>
                                        <div class="text-center mt-3 text-muted small">Belum ada item terjual pada periode ini.</div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Riwayat Transaksi</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>ID</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                            <th width="12%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(mysqli_num_rows($query_orders) > 0): ?>
                                            <?php while($row = mysqli_fetch_assoc($query_orders)): ?>
                                                <?php 
                                                    $st = $row['status'];
                                                    $badge = 'bg-secondary';
                                                    if($st=='pending') $badge='bg-pending';
                                                    elseif($st=='proses') $badge='bg-proses';
                                                    elseif($st=='success' || $st=='settlement' || $st=='capture') $badge='bg-success';
                                                    elseif($st=='expire' || $st=='deny' || $st=='cancel') $badge='bg-failed';
                                                ?>
                                            <tr>
                                                <td class="font-weight-bold">#<?= substr($row['id'], -6) ?></td>
                                                <td><?= date('d/m/y H:i', strtotime($row['created_at'])) ?></td>
                                                <td>
                                                    <?= htmlspecialchars($row['customer_name']) ?><br>
                                                    <small class="text-muted"><?= $row['customer_phone'] ?></small>
                                                </td>
                                                <td>Rp <?= number_format($row['total_amount'], 0, ',', '.') ?></td>
                                                <td class="text-center"><span class="status-badge <?= $badge ?>"><?= strtoupper($st) ?></span></td>
                                                <td class="text-center">
                                                    <button class="btn btn-info btn-circle btn-sm" data-toggle="modal" data-target="#modalDetail<?= $row['id'] ?>"><i class="fas fa-eye"></i></button>
                                                    <button class="btn btn-warning btn-circle btn-sm" data-toggle="modal" data-target="#modalStatus<?= $row['id'] ?>"><i class="fas fa-edit"></i></button>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="modalDetail<?= $row['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Detail Order</h5>
                                                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p><strong>Nama:</strong> <?= $row['customer_name'] ?></p>
                                                            <p><strong>Alamat:</strong> <?= $row['customer_address'] ?></p>
                                                            <hr><h6>Item:</h6>
                                                            <ul class="list-group list-group-flush">
                                                                <?php
                                                                $oid = $row['id'];
                                                                $q_item = mysqli_query($koneksi, "SELECT * FROM order_items WHERE order_id='$oid'");
                                                                while($item = mysqli_fetch_assoc($q_item)):
                                                                ?>
                                                                <li class="list-group-item d-flex justify-content-between px-0">
                                                                    <?= $item['product_name'] ?> (x<?= $item['quantity'] ?>)
                                                                    <span><?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></span>
                                                                </li>
                                                                <?php endwhile; ?>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="modal fade" id="modalStatus<?= $row['id'] ?>" tabindex="-1">
                                                <div class="modal-dialog modal-sm">
                                                    <div class="modal-content">
                                                        <div class="modal-header"><h5 class="modal-title">Update Status</h5></div>
                                                        <form method="POST">
                                                            <div class="modal-body">
                                                                <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                                                                <select name="status" class="form-control">
                                                                    <option value="pending" <?= ($st=='pending')?'selected':'' ?>>Pending</option>
                                                                    <option value="proses" <?= ($st=='proses')?'selected':'' ?>>Diproses</option>
                                                                    <option value="success" <?= ($st=='success')?'selected':'' ?>>Selesai</option>
                                                                    <option value="cancel" <?= ($st=='cancel')?'selected':'' ?>>Batal</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="submit" name="btn_update_status" class="btn btn-primary btn-sm btn-block">Simpan</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endwhile; ?>
                                        <?php else: ?>
                                            <tr><td colspan="6" class="text-center py-4">Tidak ada data pesanan pada periode ini.</td></tr>
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

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>
    
    <script src="../../../assets/template/sbadmin2/vendor/chart.js/Chart.min.js"></script>

    <script>
        // Default Config
        Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
        Chart.defaults.global.defaultFontColor = '#858796';

        function number_format(number) {
            return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number);
        }

        // 1. AREA CHART (TREN)
        var ctxArea = document.getElementById("myAreaChart");
        var myAreaChart = new Chart(ctxArea, {
            type: 'line',
            data: {
                labels: <?= json_encode($chart_trend_label); ?>,
                datasets: [{
                    label: "Pendapatan",
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: <?= json_encode($chart_trend_data); ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                scales: {
                    xAxes: [{ gridLines: { display: false, drawBorder: false }, ticks: { maxTicksLimit: 7 } }],
                    yAxes: [{ 
                        ticks: { maxTicksLimit: 5, padding: 10, callback: function(value) { return 'Rp ' + value.toLocaleString('id-ID'); } },
                        gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] }
                    }],
                },
                legend: { display: false },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14, borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, intersect: false, mode: 'index', caretPadding: 10,
                    callbacks: { label: function(tooltipItem, chart) { return 'Pendapatan: ' + number_format(tooltipItem.yLabel); } }
                }
            }
        });

        // 2. PIE CHART (STATUS)
        var ctxPie = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctxPie, {
            type: 'doughnut',
            data: {
                labels: <?= json_encode($chart_status_label); ?>,
                datasets: [{
                    data: <?= json_encode($chart_status_data); ?>,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf', '#dda20a', '#be2617'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: { backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, caretPadding: 10 },
                legend: { display: true, position: 'bottom' },
                cutoutPercentage: 80,
            },
        });

        // 3. BAR CHART (TOP PRODUK)
        var ctxBar = document.getElementById("myBarChart");
        var myBarChart = new Chart(ctxBar, {
            type: 'horizontalBar', // Horizontal biar nama panjang muat
            data: {
                labels: <?= json_encode($chart_prod_label); ?>,
                datasets: [{
                    label: "Terjual",
                    backgroundColor: "#1cc88a",
                    hoverBackgroundColor: "#17a673",
                    borderColor: "#1cc88a",
                    data: <?= json_encode($chart_prod_data); ?>,
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: { padding: { left: 10, right: 25, top: 25, bottom: 0 } },
                scales: {
                    xAxes: [{ ticks: { min: 0, padding: 10 }, gridLines: { color: "rgb(234, 236, 244)", zeroLineColor: "rgb(234, 236, 244)", drawBorder: false, borderDash: [2], zeroLineBorderDash: [2] } }],
                    yAxes: [{ gridLines: { display: false, drawBorder: false }, maxBarThickness: 25 }],
                },
                legend: { display: false },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)", bodyFontColor: "#858796", titleMarginBottom: 10, titleFontColor: '#6e707e', titleFontSize: 14, borderColor: '#dddfeb', borderWidth: 1, xPadding: 15, yPadding: 15, displayColors: false, intersect: false, mode: 'index', caretPadding: 10,
                    callbacks: { label: function(tooltipItem, chart) { return 'Terjual: ' + tooltipItem.xLabel + ' Porsi'; } }
                }
            }
        });
    </script>
</body>
</html>