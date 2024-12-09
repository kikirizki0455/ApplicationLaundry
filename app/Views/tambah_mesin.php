<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Tambah Mesin Baru</h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <b>Gagal</b>
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <form action="<?= site_url('storeMesin') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="form-group">
                    <label>Nama Mesin</label>
                    <input type="text" name="nama_mesin" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Kapasitas</label>
                    <input type="text" name="kapasitas" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Aktif">Aktif</option>
                        <option value="Non-Aktif">Non-Aktif</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <select name="kategori" class="form-control" required>
                        <option value="infeksi">Infeksi</option>
                        <option value="non_infeksi">Non-Infeksi</option>
                    </select>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-success">Simpan</button>
                    <a href="<?= site_url('data_mesin') ?>" class="btn btn-secondary">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>