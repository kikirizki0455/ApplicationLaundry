<!-- app/Views/layout/sidebar.php -->
<?php $role = session()->get('role'); ?>

<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="<?= base_url() ?>">
                <img src="<?= base_url('template/assets/img/logo/logo-dustira1.jpeg') ?>" alt="Logo" style="height: 50px; width: auto;">
            </a>
            <h3> LA-DUS</h3>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="<?= base_url() ?>">LA</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Laundry Dustira</li>
            <?php if (in_array($role, ['admin'])): ?>
                <li class="active">
                    <a class="nav-link" href="<?= site_url('dashboard') ?>">
                        <i class="far fa-square"></i> <span>Beranda</span>
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a href="#" class="nav-link has-dropdown" id="dataManagementDropdown" aria-expanded="false">
                        <i class="fas fa-th"></i> <span>Data Management</span>
                    </a>
                    <ul class="dropdown-menu" id="dropdownMenu" aria-labelledby="dataManagementDropdown">
                        <li><a class="nav-link" href="<?= site_url('data_pegawai') ?>">Pegawai</a></li>
                        <li><a class="nav-link" href="<?= site_url('data_barang') ?>">Barang</a></li>
                        <li><a class="nav-link" href="<?= site_url('data_bahan') ?>">Bahan</a></li>
                        <li><a class="nav-link" href="<?= site_url('data_mesin') ?>">Mesin</a></li>
                        <li><a class="nav-link" href="<?= site_url('data_ruangan') ?>">Ruang</a></li>
                    </ul>
                </li>

            <?php endif; ?>

            <?php if (in_array($role, ['admin', 'pengelola'])): ?>
                <li class="active">
                    <a class="nav-link" href="<?= site_url('pengelolaan/pengelola') ?>">
                        <i class="far fa-calendar"></i> <span>Pengelola</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if (in_array($role, ['admin', 'distribusi'])): ?>
                <li class="active">
                    <a class="nav-link" href="<?= site_url('distribusi') ?>">
                        <i class="far fa-calendar"></i> <span>Distribusi</span>
                    </a>
                </li>
            <?php endif; ?>

            <?php if ($role === 'admin'): ?>
                <li class="active">
                    <a class="nav-link" href="<?= site_url('laporan/laporan') ?>">
                        <i class="far fa-book"></i> <span>Laporan</span>
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </aside>
</div>


<!-- Overlay untuk sidebar mobile -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<style>
    /* CSS Responsif untuk Sidebar */
    @media (max-width: 768px) {
        .main-sidebar {
            position: fixed;
            top: 0;
            left: -300px;
            /* Sembunyikan sidebar di luar layar */
            width: 300px;
            height: 100%;
            z-index: 1050;
            background-color: #ffffff;
            transition: left 0.3s ease;
            overflow-y: auto;
        }

        .main-sidebar.active {
            left: 0;
            /* Tampilkan sidebar */
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }

        .sidebar-overlay.active {
            display: block;
        }

        .sidebar-toggler {
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: #000;
            font-size: 24px;
            cursor: pointer;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const navbarToggle = document.querySelector('[data-bs-toggle="sidebar"]');

        // Fungsi untuk membuka sidebar
        function openSidebar() {
            sidebar.classList.add('active');
            sidebarOverlay.classList.add('active');
        }

        // Fungsi untuk menutup sidebar
        function closeSidebar() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        }

        // Toggle sidebar dari navbar
        if (navbarToggle) {
            navbarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                openSidebar();
            });
        }

        // Toggle sidebar dari tombol close
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', closeSidebar);
        }

        // Tutup sidebar saat klik di overlay
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Dropdown di sidebar
        const dropdown = document.getElementById('dataManagementDropdown');
        const dropdownMenu = document.getElementById('dropdownMenu');

        if (dropdown && dropdownMenu) {
            dropdown.addEventListener('click', function(event) {
                event.preventDefault();
                const isExpanded = dropdown.getAttribute('aria-expanded') === 'true';
                dropdown.setAttribute('aria-expanded', !isExpanded);
                dropdownMenu.style.display = isExpanded ? 'none' : 'block';
            });
        }
    });
</script>