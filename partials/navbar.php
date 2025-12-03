
<header id="header" class="header d-flex align-items-center sticky-top">
    <div class="container position-relative d-flex align-items-center">
      <a href="/" class="logo d-flex align-items-center me-auto">
        <h1 class="sitename">D'Bubuy Ma'Atik</h1>
      </a>
      
      <nav id="navmenu" class="navmenu">
        <ul>
            <li><a href="index.php#hero" class="active">Home</a></li>
            <li><a href="index.php#features">Katalog Menu</a></li>
            <li><a href="index.php#liputan-media">Media</a></li>
            <li><a href="index.php#contact">Location</a></li>
            <li><a href="https://wa.link/esi7xm">Kontak</a></li>

            <li>
                <a href="#" data-bs-toggle="modal" data-bs-target="#modalKeranjang" class="d-flex align-items-center">
                    <i class="bi bi-cart-fill" style="font-size: 1.2rem;"></i>
                    <?php 
                    // Cek session cart 
                    $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
                    if($cartCount > 0): 
                    ?>
                        <span class="badge bg-danger rounded-pill ms-1"><?= $cartCount ?></span>
                    <?php endif; ?>
                </a>
            </li>

            <?php if (isset($_SESSION['is_login']) && $_SESSION['is_login'] === true): ?>
                
                <li class="dropdown">
                    <a href="#">
                        <span>Halo, <strong><?= htmlspecialchars($_SESSION['nama']); ?></strong></span> 
                        <i class="bi bi-chevron-down toggle-dropdown"></i>
                    </a>
                    <ul>
                        <?php if (isset($_SESSION['kategori']) && $_SESSION['kategori'] == 'admin'): ?>
                            <li><a href="backend/admin/index_admin.php" style="color: #e43c5c; font-weight: bold;">Dashboard Admin</a></li>
                            <li><hr class="dropdown-divider"></li>
                        <?php endif; ?>

                        <li><a href="pages/profile.php">Profil Saya</a></li>
                        <li><a href="pages/orders.php">Pesanan Saya</a></li> <li><hr class="dropdown-divider"></li>
                        <li><a href="action/logout.php" style="color: red;">Logout</a></li>
                    </ul>
                </li>

            <?php else: ?>
                <li><a href="pages/login.php" class="btn-get-started" style="margin-left: 15px;">Login</a></li>
            <?php endif; ?>
            </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>
    </div>
</header>