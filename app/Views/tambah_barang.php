<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('data_barang'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Tambah Barang</h4>

        </div>
        <div class="card-body col-md-15">
            <?php if (session()->getFlashdata('error_barang')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>

                        <?= session()->getFlashdata('error_barang'); ?>
                    </div>
                </div>
            <?php endif; ?>

            <form action="<?= site_url('DataBarang/store_barang'); ?>" method="POST" autocomplete="off">
                <?= csrf_field(); ?>
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stok_barang">Stok Barang</label>
                    <input type="text" name="stok" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('data_barang'); ?>" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</section>

<?= $this->endSection(); ?>