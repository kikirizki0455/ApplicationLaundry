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
    <h1 class="text-center mb-4">Dasbor Pengelolaan</h1>
    <div class="row">
        <!-- Card Pencucian -->
        <div class="col-md-6 mb-4">
            <div class="card card-primary">
                <div class="card-header">

                    <h4>Pencucian</h4>
                </div>
                <div class="card-body">
                    <p>Proses pencucian pakaian.</p>
                    <a href="<?= site_url('pengelolaan/pencucian'); ?>" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card card-warning">
                <div class="card-header">
                    <h4>Pengeringan</h4>
                </div>
                <div class="card-body">
                    <p>Proses pengeringan pakaian.</p>
                    <a href="<?= site_url('pengelolaan/pengeringan'); ?>" class="btn btn-warning">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card card-info">
                <div class="card-header">
                    <h4>Penyetrikaan</h4>
                </div>
                <div class="card-body">
                    <p>Proses penyetrikaan pakaian.</p>
                    <a href="<?= site_url('pengelolaan/penyetrikaan'); ?>" class="btn btn-info">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card card-success">
                <div class="card-header">
                    <h4>Pelipatan</h4>
                </div>
                <div class="card-body">
                    <p>Proses pelipatan pakaian.</p>
                    <a href="<?= site_url('pengelolaan/pelipatan'); ?>" class="btn btn-success">Masuk</a>
                </div>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection(); ?>