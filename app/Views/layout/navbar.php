<nav class="navbar-elite navbar-expand-lg main-navbar">
    <ul class="navbar-nav mr-auto">
        <li class="nav-item">
            <a href="#" data-bs-toggle="sidebar" class="nav-link nav-link-lg">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav">
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img alt="image" src="<?= base_url() ?>/template/assets/img/avatar/avatar-1.png" class="rounded-circle mr-1" style="width: 30px; height: 30px;">
                <span class="d-none d-lg-inline">
                    Hi, <?= session()->get('nama') ?? 'Guest' ?>
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <div class="dropdown-header">
                        <?= session()->get('welcome_message') ?? 'Selamat datang!' ?>
                    </div>
                </li>
                <li>
                    <a href="/profile" class="dropdown-item">
                        <i class="far fa-user"></i> Profil
                    </a>
                </li>
                <li>
                    <a href="/settings" class="dropdown-item">
                        <i class="fas fa-cog"></i> Pengaturan
                    </a>
                </li>
                <li>
                    <hr class="dropdown-divider">
                </li>
                <li>
                    <a href="<?= site_url('auth/logout') ?>" class="dropdown-item text-danger logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Keluar
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const logoutButtons = document.querySelectorAll('.logout-btn');

        logoutButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: 'Konfirmasi Keluar',
                    text: 'Apakah Anda yakin ingin keluar?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Keluar',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = '<?= site_url('auth/logout') ?>';
                    }
                });
            });
        });
    });
</script>