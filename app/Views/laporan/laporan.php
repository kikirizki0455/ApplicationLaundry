<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<div class="container mt-5">
    <h1 class="text-center mb-4">Laporan </h1>
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card bg-light border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Laporan Pengelolaan</h5>
                    <p class="card-text">Lihat Laporan Pengelolaan</p>
                    <a href="<?= site_url('laporan/laporan_pengelolaan'); ?>" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card bg-light border-light shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Laporan Distribusi</h5>
                    <p class="card-text">Lihat Laporan Distribusi</p>
                    <a href="<?= site_url('laporan/laporan_distribusi'); ?>" class="btn btn-primary">Masuk</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>