<style>
    #sidebar {
        width: 280px;
        background: rgba(228, 200, 236, 0.2); 
        backdrop-filter: blur(14px);
        height: 100vh;
        position: fixed;
        left: 0;
        top: 0;
        z-index: 100;
        transition: all 0.3s ease-in-out;
        box-shadow: 4px 0 10px rgba(0, 0, 0, 0.08);
        border-right: 1px solid rgba(255, 255, 255, 0.2);
    }

    .sidebar-wrapper {
        padding: 25px 20px;
        height: 100%;
        overflow-y: auto;
    }

    .sidebar-header img {
        width: 100px;
        height: auto;
        margin-bottom: 10px;
        filter: drop-shadow(0 0 5px rgba(106, 76, 147, 0.3)); 
    }

    .sidebar-title {
        font-size: 16px;
        text-transform: uppercase;
        color: #6a4c93;
        font-weight: bold;
        margin: 20px 0 10px;
        letter-spacing: 1px;
    }

    .sidebar-menu .menu {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .sidebar-item {
        margin-bottom: 10px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        padding: 12px 16px;
        border-radius: 8px;
        color: #3c3c3c !important; 
        font-weight: 500;
        text-decoration: none;
        background-color: transparent !important; 
        transition: background-color 0.2s, padding-left 0.2s, color 0.2s;
    }

    .sidebar-link:hover,
    .sidebar-item.active .sidebar-link {
        background-color: rgba(106, 76, 147, 0.3) !important; 
        color: #fff !important;
        font-weight: bold;
        padding-left: 20px;
    }

    .sidebar-item.active .sidebar-link {
        background-color: rgba(106, 76, 147, 0.4) !important; 
        color: #ffffff !important; 
        font-weight: bold;
    }

    .has-sub .submenu {
        margin-left: 20px;
        margin-top: 5px;
    }

    .submenu-item a {
        display: block;
        padding: 8px 16px;
        border-radius: 6px;
        color: #4a4a6a;
        font-size: 0.95rem;
        text-decoration: none;
        transition: background-color 0.2s;
    }

    .submenu-item a:hover {
        background-color: rgba(106, 76, 147, 0.2) !important; 
        color: #ffffff !important; 
    }

    .sidebar-header img {
        filter: drop-shadow(0 0 5px rgba(106, 76, 147, 0.3)); 
    }

</style>


<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header text-center">
            <h2 class="auth-logo">
                <img src="<?= base_url('assets/images/meowgic.png') ?>" alt="PetShop Logo" class="logo-img">
            </h2>
        </div>

        <?php $level = session()->get('level'); ?>

        <div class="sidebar-menu">
            <ul class="menu">

                <li class="sidebar-item">
                    <a href="<?= base_url('home/dashboard') ?>" class="sidebar-link">
                        <i class="bi bi-house-heart-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                <?php if ($level == 1 || $level == 4): ?>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-person-hearts"></i>
                        <span>Data Master</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="<?= base_url('home/pelanggan') ?>">
                                <i class="bi bi-person-lines-fill"></i> Pelanggan
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/petugas') ?>">
                                <i class="bi bi-person-vcard-fill"></i> Petugas
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/user') ?>">
                                <i class="bi bi-person-gear"></i> User
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($level == 1 || $level == 3 || $level == 4):?>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-bag-heart-fill"></i>
                        <span>Data Produk</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="<?= base_url('home/produk') ?>">
                                <i class="bi bi-box-seam"></i> Produk
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/grooming') ?>">
                                <i class="bi bi-scissors"></i> Layanan Grooming
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($level == 1 || $level == 3 || $level == 4):?>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-receipt-cutoff"></i>
                        <span>Data Pemesanan</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="<?= base_url('home/rekap_pesanan') ?>">
                                <i class="bi bi-cart-check-fill"></i> Rekap Pesanan Produk
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/rekap_grooming') ?>">
                                <i class="bi bi-scissors"></i> Rekap Layanan Grooming
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/pembayaran') ?>">
                                <i class="bi bi-cash-coin"></i> Data Pembayaran
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if ($level == 1 || $level == 3 || $level == 4): ?>
                <li class="sidebar-item">
                    <a href="<?= base_url('home/pengiriman') ?>" class="sidebar-link">
                        <i class="bi bi-truck-front-fill"></i> 
                        <span>Data Pengiriman</span>
                    </a>
                </li>
                <?php endif; ?>

                <?php if ($level == 1 || $level == 4): ?>
                <li class="sidebar-item has-sub">
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Laporan</span>
                    </a>
                    <ul class="submenu">
                        <li class="submenu-item">
                            <a href="<?= base_url('home/lpn') ?>">
                                <i class="bi bi-clipboard-data-fill"></i> Laporan Pemesanan
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/lpgrooming') ?>">
                                <i class="bi bi-scissors"></i> Laporan Grooming
                            </a>
                        </li>
                        <li class="submenu-item">
                            <a href="<?= base_url('home/lpkeuangan') ?>">
                                <i class="bi bi-wallet-fill"></i> Laporan Keuangan
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="sidebar-item">
                    <a href="<?= base_url('home/log') ?>" class="sidebar-link">
                        <i class="bi bi-clock-history"></i> 
                        <span>Riwayat Aktivitas</span>
                    </a>
                </li>

                <?php if ($level == 4): ?>
                <li class="sidebar-item">
                    <a href="<?= base_url('home/settings') ?>" class="sidebar-link">
                        <i class="bi bi-sliders2-vertical"></i> 
                        <span>Settings</span>
                    </a>
                </li>
                <?php endif; ?>

                <li class="sidebar-item">
                    <a href="<?= base_url('home/profile') ?>" class="sidebar-link">
                        <i class="bi bi-person-circle"></i>
                        <span>Profile</span>
                    </a>
                </li>


            </ul>
        </div>
    </div>
</div>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
