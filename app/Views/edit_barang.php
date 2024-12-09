<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">

            <div class="section-header-button">
                <a href="<?= site_url('data_barang'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Edit Barang</h4>
        </div>
        <div class="card-body col-md-15">
            <form action="<?= site_url('update_barang'); ?>" method="POST" autocomplete="off">
                <?= csrf_field(); ?>
                <input type="hidden" name="id_barang" value="<?= esc($barang->id_barang); ?>">
                <div class="form-group">
                    <label for="nama_barang">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="stok">Stok Barang</label>
                    <input type="text" name="stok" class="form-control" value="<?= esc($barang->stok); ?>" required>
                </div>
                <button type="submit" class="btn btn-success">Simpan</button>
                <a href="<?= site_url('data_barang'); ?>" class="btn btn-secondary">Batal</a>
        </div>
</section>
<!-- modal barang -->
<div class="modal fade" id="modalTambahBahan" tabindex="-1" role="dialog" aria-labelledby="modalTambahBahanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBahanLabel">Tambah Bahan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('DataManagement/DataMesin/storeBahan'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_mesin" id="modal_id_mesin" value="">
                    <div id="bahanContainer">
                        <div class="form-group d-flex align-items-center mb-2">
                            <select name="id_bahan[]" class="form-control me-2" required>
                                <option value="" disabled selected>Pilih Bahan</option>
                            </select>
                            <input type="number" name="jumlah[]" class="form-control me-2" placeholder="Jumlah (ml)" required min="0">
                            <button type="button" class="btn btn-success tambahBahan me-2">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger removeBahan">
                                <i class="fas fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection(); ?>