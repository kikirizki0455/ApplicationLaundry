<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Ruangan</h4>
        </div>
        <div class="card-body">

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>

                        <?= session()->getFlashdata('error'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <form action="<?= site_url('create') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Nama Ruangan</label>
                    <input type="text" name="nama_ruangan" class="form-control" required>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= site_url('data_ruangan') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>