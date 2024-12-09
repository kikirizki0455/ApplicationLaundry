<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('data_bahan'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>

            <h4>Edit Bahan</h4>
        </div>
        <div class="card-body col-md-15">
            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">x</button>
                        <b>Gagal</b>
                        <?= session()->getFlashdata('error'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <form action="<?= site_url('update_bahan'); ?>" method="POST" autocomplete="off">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_bahan" value="<?= esc($bahan->id_bahan); ?>">
                <div class="form-group">
                    <label for="nama_bahan">Nama Bahan </label>
                    <select name="nama_bahan" class="form-control" required>
                        <option value="" disabled selected>Select item</option>
                        <option value="ditergen liquid">ditergen liquid</option>
                        <option value="penetral">penetral</option>
                        <option value="concenrated oxygen bleach">concenrated oxygen bleach</option>
                        <option value="karbol sere">karbol sere</option>
                        <option value="soklin">soklin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="stok">Stok bahan</label>
                    <input type="text" name="stok_bahan" class="form-control" value="<?= esc($bahan->stok_bahan); ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('data_bahan'); ?>" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</section>

<?= $this->endSection(); ?>