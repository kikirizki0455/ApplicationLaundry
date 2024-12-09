<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">


    <div class="card">
        <div class="card-header">
            <h4>Data Bahan</h4>
            <div class="section-header-button">
                <a href="<?= site_url('tambah_bahan'); ?>" class="btn btn-success">Tambah Bahan</a>
            </div>
        </div>
        <?php if (session()->getFlashdata('success_bahan')) : ?>
            <div class="alert alert-success alert-dismissible show fade">
                <div class="alert-body">
                    <button class="close" data-dismiss="alert">x</button>
                    <b>Berhasil</b>
                    <?= session()->getFlashdata('success'); ?>
                </div>
            </div>
        <?php endif; ?>
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
                            <th>Nama Bahan</th>
                            <th>Stok</th>
                            <th>Aksi</th> <!-- Action column -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bahan as $key => $value): ?>
                            <tr>
                                <td><?= esc($key + 1); ?></td>
                                <td><?= esc($value->nama_bahan); ?></td>
                                <td><?= esc($value->stok_bahan); ?></td>
                                <td>
                                    <!-- edit barang -->
                                    <a href="javascript:void(0);"
                                        class="btn btn-primary"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal"
                                        onclick="loadEditForm('<?= esc($value->id_bahan); ?>')">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <!-- hapus data barang -->
                                    <form action="<?= site_url('delete_bahan/' . esc($value->id_bahan)); ?>" method="POST" style="display:inline;">
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
                <form action="<?= site_url('update_bahan'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <div id="bahanContainer">
                    </div>
                    <input type="hidden" name="id_bahan" id="modal_id_bahan" value="">
                    <input type="number" name="stok_bahan[]" class="form-control me-2" placeholder="Jumlah (ml)" required min="0">
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
    function loadEditForm(idBahan) {
        // Reset form sebelum menampilkan modal
        $('#modal_id_bahan').val(idBahan);
        $('#bahanContainer').html('');

        // Lakukan AJAX request untuk mengambil nama barang berdasarkan ID
        fetch('<?= site_url("get_bahan_data/"); ?>' + idBahan)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#bahanContainer').prepend(
                        '<p>Nama Bahan: ' + data.nama_bahan + '</p>'
                    );
                } else {
                    $('#bahanContainer').prepend(
                        '<p>Gagal memuat data Bahan.</p>'
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