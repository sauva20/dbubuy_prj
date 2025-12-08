<?php
session_start();
require_once '../../../config/koneksi.php';

// Cek Login Admin
if (!isset($_SESSION['is_login']) || $_SESSION['kategori'] != 'admin') {
    header("Location: ../../../pages/login.php");
    exit;
}

// --- LOGIC SIMPAN DATA ---
if (isset($_POST['btn_simpan'])) {
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $link = mysqli_real_escape_string($koneksi, $_POST['link']);
    
    // Upload Gambar
    $foto_nama = $_FILES['gambar']['name'];
    $foto_tmp = $_FILES['gambar']['tmp_name'];
    
    if($foto_nama != "") {
        $nama_baru = "media_" . time() . "_" . $foto_nama;
        $tujuan = "../../../assets/img/" . $nama_baru;
        $db_path = "assets/img/" . $nama_baru; 
        
        if (move_uploaded_file($foto_tmp, $tujuan)) {
            $query = "INSERT INTO liputan (media_name, description, image, link_url) VALUES ('$nama', '$deskripsi', '$db_path', '$link')";
            if (mysqli_query($koneksi, $query)) {
                
                // [HISTORI] CATAT LOG
                if(function_exists('catat_log')) catat_log($koneksi, "Tambah Media", "Menambahkan media baru: $nama");

                echo "<script>alert('Media berhasil ditambahkan!'); window.location='media.php';</script>";
            } else {
                echo "<script>alert('Gagal simpan ke database.');</script>";
            }
        } else {
            echo "<script>alert('Gagal upload gambar.');</script>";
        }
    } else {
        echo "<script>alert('Harap pilih logo media!');</script>";
    }
}

// --- LOGIC HAPUS ---
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $q_cek = mysqli_query($koneksi, "SELECT * FROM liputan WHERE id='$id'");
    $data = mysqli_fetch_assoc($q_cek);
    
    $path_gambar = "../../../" . $data['image'];
    if (file_exists($path_gambar)) { unlink($path_gambar); }
    
    $delete = mysqli_query($koneksi, "DELETE FROM liputan WHERE id='$id'");
    if ($delete) {
        
        // [HISTORI] CATAT LOG
        $nm = $data['media_name'] ?? 'ID '.$id;
        if(function_exists('catat_log')) catat_log($koneksi, "Hapus Media", "Menghapus media: $nm");

        echo "<script>alert('Data berhasil dihapus!'); window.location='media.php';</script>";
    }
}

$query_media = mysqli_query($koneksi, "SELECT * FROM liputan ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Media - Admin D'Bubuy</title>
    
    <link href="../../../assets/template/sbadmin2/vendor/fontawesome-free/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="../../../assets/template/sbadmin2/css/sb-admin-2.min.css" rel="stylesheet">
    <link href="../../../assets/css/admin.css" rel="stylesheet"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <style>
        .img-table { width: 80px; height: 50px; object-fit: contain; border: 1px solid #ddd; background: #fff; padding: 2px; border-radius: 4px; }

        /* --- NETFLIX STYLE CSS --- */
        .preview-area {
            background-color: #eef2f7; border: 2px dashed #ccc; border-radius: 10px;
            padding: 50px; display: flex; justify-content: center; align-items: flex-start;
            min-height: 400px; overflow: visible; 
        }

        .media-container { position: relative; width: 250px; perspective: 1000px; z-index: 10; }

        .media-logo-box {
            background: #fff; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            padding: 20px; height: 140px; display: flex; align-items: center; justify-content: center;
            border: 1px solid #ddd; transition: 0.3s; cursor: pointer; position: relative; z-index: 1;
        }
        .media-logo-box img { max-height: 80px; max-width: 100%; object-fit: contain; filter: grayscale(100%); transition: 0.3s; }
        
        .media-popup-card {
            display: none; position: absolute; top: -30%; left: -10%; width: 120%;
            background-color: #141414; color: #fff; border-radius: 10px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.6); z-index: 9999; overflow: hidden;
            animation: popUp 0.3s forwards;
        }

        @keyframes popUp { 0% { opacity: 0; transform: scale(0.8); } 100% { opacity: 1; transform: scale(1); } }

        .popup-video { position: relative; width: 100%; height: 160px; background: #000; }
        .popup-video iframe, .popup-video img { width: 100%; height: 100%; border: none; object-fit: cover; }

        .popup-info { padding: 15px; text-align: left; }
        .popup-info h5 { font-size: 16px; font-weight: 700; margin-bottom: 5px; color: #e50914; }
        .popup-info p { font-size: 11px; color: #b3b3b3; line-height: 1.4; margin-bottom: 10px; }

        .btn-visit {
            background-color: #fff; color: #000; padding: 5px 15px; border-radius: 4px;
            font-size: 12px; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center; gap: 5px;
        }
        
        .media-container:hover .media-logo-box img { filter: grayscale(0%); }
        .media-container:hover .media-popup-card { display: block; }
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Liputan Media</h1>
                        <button class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalTambah">
                            <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Media Baru
                        </button>
                    </div>

                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar Media</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle" width="100%" cellspacing="0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Logo</th>
                                            <th>Nama Media</th>
                                            <th>Deskripsi & Link</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1;
                                        if(mysqli_num_rows($query_media) > 0):
                                            while($row = mysqli_fetch_assoc($query_media)): 
                                                $img_src = "../../../" . $row['image'];
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td class="text-center"><img src="<?= $img_src ?>" class="img-table"></td>
                                            <td class="font-weight-bold"><?= $row['media_name'] ?></td>
                                            <td>
                                                <p class="small text-muted mb-1"><?= substr($row['description'], 0, 80) ?>...</p>
                                                <a href="<?= $row['link_url'] ?>" target="_blank" class="badge badge-info"><i class="fas fa-link"></i> Link</a>
                                            </td>
                                            <td class="text-center">
                                                <a href="media.php?hapus=<?= $row['id'] ?>" class="btn btn-danger btn-sm btn-circle" onclick="return confirm('Hapus?')"><i class="fas fa-trash"></i></a>
                                            </td>
                                        </tr>
                                        <?php endwhile; else: ?>
                                            <tr><td colspan="5" class="text-center py-4">Belum ada data media.</td></tr>
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
    <div class="modal fade" id="modalTambah" tabindex="-1" role="dialog" aria-hidden="true" style="z-index: 1050;">
        <div class="modal-dialog modal-xl" role="document"> 
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title font-weight-bold">Input Media Baru</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="row">
                            
                            <div class="col-lg-5">
                                <h6 class="font-weight-bold text-gray-800 mb-3">Data Media</h6>
                                <div class="form-group">
                                    <label>Nama Media</label>
                                    <input type="text" name="nama" id="inpName" class="form-control" placeholder="Contoh: Trans7" required>
                                </div>
                                <div class="form-group">
                                    <label>Logo Media</label>
                                    <div class="custom-file">
                                        <input type="file" name="gambar" class="custom-file-input" id="inpFile" accept="image/*" required>
                                        <label class="custom-file-label" for="inpFile">Pilih Logo...</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Link Video (YouTube) / Artikel</label>
                                    <input type="url" name="link" id="inpLink" class="form-control" placeholder="https://youtube.com/..." required>
                                </div>
                                <div class="form-group">
                                    <label>Deskripsi Singkat</label>
                                    <textarea name="deskripsi" id="inpDesc" class="form-control" rows="3" placeholder="Deskripsi..." required></textarea>
                                </div>
                            </div>

                            <div class="col-lg-7 border-left">
                                <h6 class="font-weight-bold text-gray-800 mb-3">Live Preview</h6>
                                <p class="small text-muted">Arahkan mouse ke logo di bawah untuk melihat efek Netflix Card.</p>
                                
                                <div class="preview-area">
                                    <div class="media-container" id="previewContainer">
                                        <div class="media-logo-box">
                                            <img src="https://via.placeholder.com/150?text=Logo" id="prevLogo" alt="Logo">
                                        </div>
                                        <div class="media-popup-card">
                                            <div class="popup-video" id="prevVideoArea">
                                                <img src="https://via.placeholder.com/300x200?text=Video+Preview" style="width:100%;height:100%;object-fit:cover;opacity:0.5;">
                                            </div>
                                            <div class="popup-info">
                                                <h5 id="prevTitle">Nama Media</h5>
                                                <p id="prevDesc">Deskripsi akan muncul di sini...</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <a href="#" class="btn-visit"><i class="bi bi-play-fill"></i> Tonton</a>
                                                    <small class="text-muted" id="prevType">Artikel</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" name="btn_simpan" class="btn btn-primary">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="../../../assets/template/sbadmin2/vendor/jquery/jquery.min.js"></script>
    <script src="../../../assets/template/sbadmin2/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../../../assets/template/sbadmin2/js/sb-admin-2.min.js"></script>

    <script>
        function getYoutubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }

        $('#inpName').on('input', function() { $('#prevTitle').text($(this).val() || 'Nama Media'); });
        $('#inpDesc').on('input', function() { 
            let val = $(this).val();
            if(val.length > 80) val = val.substring(0,80) + '...';
            $('#prevDesc').text(val || 'Deskripsi...'); 
        });

        $('#inpFile').change(function() {
            const file = this.files[0];
            if(file){
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#prevLogo').attr('src', e.target.result);
                    if(!$('#prevVideoArea iframe').length) {
                        $('#prevVideoArea img').attr('src', e.target.result);
                    }
                }
                reader.readAsDataURL(file);
                $('.custom-file-label').text(file.name);
            }
        });

        $('#inpLink').on('input', function() {
            const url = $(this).val();
            const ytId = getYoutubeId(url);
            const videoArea = $('#prevVideoArea');
            const typeLabel = $('#prevType');

            if(ytId) {
                const embedUrl = `https://www.youtube.com/embed/${ytId}?autoplay=1&mute=1&controls=0&modestbranding=1&rel=0`;
                videoArea.html(`<iframe src="${embedUrl}" allow="autoplay"></iframe>`);
                typeLabel.text('YouTube');
            } else {
                const currentLogo = $('#prevLogo').attr('src');
                videoArea.html(`<img src="${currentLogo}" style="width:100%;height:100%;object-fit:cover;opacity:0.5;">`);
                typeLabel.text('Artikel');
            }
        });
    </script>

</body>
</html>