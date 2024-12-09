<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">

            <div class="section-header-button">
                <a href="<?= site_url('data_bahan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Tambah Bahan</h4>
        </div>
        <div class="card-body col-md-15">
        </div>
        <div class="card-body col-md-15">
            <?php if (session()->getFlashdata('error_bahan')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <?= is_array(session()->getFlashdata('error_bahan')) ? implode(', ', session()->getFlashdata('error_bahan')) : session()->getFlashdata('error_bahan'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <form action="<?= site_url('store_bahan'); ?>" method="POST" autocomplete="off">

                <?= csrf_field(); ?>
                <div class="form-group">
                    <label for="nama_bahan">Nama Bahan </label>
                    <input type="text" name="nama_bahan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stok_barang">Stok Bahan</label>
                    <input type="text" name="stok_bahan" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('data_bahan'); ?>" class="btn btn-secondary">Batal</a>
            </form>

        </div>
    </div>
</section>

<?= $this->endSection(); ?>