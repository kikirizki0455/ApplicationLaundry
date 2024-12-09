<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<div class="container mt-5">
    <?php if (session()->getFlashdata('welcome_message')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Selamat datang, <?= esc(session('nama')) ?>!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger  alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
    <h1 class="text-center mb-4">Dasbor Distribusi</h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-light border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Timbangan Kotor</h5>
                    <p class="card-text">Kelola Barang Kotor Disini</p>
                    <a href="<?= site_url('distribusi/timbangan_kotor'); ?>" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-light border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Timbangan Bersih</h5>
                    <p class="card-text">Kelola Barang Bersih Disini</p>
                    <a href="<?= site_url('distribusi/timbangan_bersih'); ?>" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>