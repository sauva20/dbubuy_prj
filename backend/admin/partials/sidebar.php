<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_admin.php">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-utensils"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin D'Bubuy</div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'index_admin.php') ? 'active' : '' ?>">
        <a class="nav-link" href="index_admin.php">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">Menu Toko</div>

    <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'produk.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pages/produk.php"> <i class="fas fa-fw fa-box"></i>
            <span>Kelola Produk</span>
        </a>
    </li>

    <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'pesanan.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pages/pesanan.php">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Pesanan</span>
        </a>
    </li>

    <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'customer.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pages/customer.php">
            <i class="fas fa-fw fa-users"></i>
            <span>Customer</span>
        </a>
    </li>

    <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'media.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pages/media.php">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>Liputan Media</span>
        </a>
    </li>

        <li class="nav-item <?= (basename($_SERVER['PHP_SELF']) == 'histori.php') ? 'active' : '' ?>">
        <a class="nav-link" href="pages/histori.php">
            <i class="fas fa-fw fa-history"></i>
            <span>Histori Aktivitas</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>