<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Data Barang</h4>
            <div class="section-header-button">
                <a href="<?= site_url('tambah_barang'); ?>" class="btn btn-success">Tambah Barang</a>
            </div>
        </div>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">x</button>
                    <b>Berhasil</b>
                    <?= session()->getFlashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">x</button>
                    <b>Gagal</b>
                    <?= session()->getFlashdata('error'); ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-md">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Total Stok</th>
                            <th>Stok Dicuci</th>
                            <th>Aksi</th> <!-- Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barang as $key => $value): ?>
                            <tr>
                                <td><?= esc($key + 1); ?></td>
                                <td><?= esc($value->nama_barang); ?></td>
                                <td><?= esc($value->total_stok); ?></td>
                                <td><?= esc($value->stok_dicuci); ?></td>
                                <td>
                                    <!-- edit barang -->

                                    <a href="javascript:void(0);"
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick="loadEditForm('<?= esc($value->id_barang); ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- hapus data barang -->
                                    <form action="<?= site_url('delete_barang/' . esc($value->id_barang)); ?>" method="POST" style="display:inline;">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="DELETE"> <!-- Method spoofing -->
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</section>
<!-- Modal Content -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Stok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="<?= site_url('update_barang'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div id="bahanContainer">
                    </div>
                    <input type="hidden" name="id_barang" id="modal_id_barang" value="">
                    <input type="number" name="stok[]" class="form-control me-2" placeholder="Jumlah (ml)" required min="0">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function loadEditForm(idBarang) {
        // Reset form sebelum menampilkan modal
        $('#modal_id_barang').val(idBarang);
        $('#bahanContainer').html('');

        // Lakukan AJAX request untuk mengambil nama barang berdasarkan ID
        fetch('<?= site_url("get_barang_data/"); ?>' + idBarang)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#bahanContainer').prepend(
                        '<p>Nama Barang: ' + data.nama_barang + '</p>'
                    );
                } else {
                    $('#bahanContainer').prepend(
                        '<p>Gagal memuat data barang.</p>'
                    );
                }
            })
            .catch(error => {
                console.error('Error:', error);
                $('#bahanContainer').prepend(
                    '<p>Terjadi kesalahan saat memuat data.</p>'
                );
            });
    }
</script>


<?= $this->endSection(); ?>