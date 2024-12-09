<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Data Mesin</h4>
            <div class="section-header-button">
                <a href="<?= site_url('tambah_mesin'); ?>" class="btn btn-success">Tambah Mesin</a>
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
                            <th>Nama Mesin</th>
                            <th>Kapasitas</th>
                            <th>Status</th>
                            <th>Kategori</th>
                            <th>Bahan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($mesin as $key => $value): ?>
                            <tr>
                                <td><?= esc($key + 1); ?></td>
                                <td><?= esc($value['nama_mesin']); ?></td>
                                <td><?= esc($value['kapasitas']); ?></td>
                                <td>
                                    <span class="badge <?= $value['status'] === 'aktif' ? 'badge-success' : 'badge-danger' ?>">
                                        <?= ucfirst(esc($value['status'])); ?>
                                    </span>
                                </td>
                                <td><?= esc($value['kategori']); ?></td>
                                <td id="bahan-list-<?= esc($value['id_mesin']); ?>">
                                    <?php if (!empty($value['bahan']) && is_array($value['bahan'])): ?>
                                        <?php

                                        $bahanList = [];

                                        foreach ($value['bahan'] as $bahan) {
                                            if (is_array($bahan) && isset($bahan['nama_bahan']) && isset($bahan['jumlah_bahan'])) {
                                                $bahanList[] = esc($bahan['nama_bahan']) . ' : (' . esc($bahan['jumlah_bahan']) . 'ml)';
                                            }
                                        }
                                        echo implode(' | ', $bahanList);
                                        ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm me-2 mr-2" onclick="modalTambahBahan('<?= esc($value['id_mesin']); ?>')">
                                        Tambah Bahan
                                    </button>
                                    <form action="<?= site_url('updateStatus/' . $value['id_mesin']); ?>" method="POST" style="display:inline;" class="mr-2">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="PUT">
                                        <input type="hidden" name="new_status" value="<?= $value['status'] === 'aktif' ? 'tidak_aktif' : 'aktif' ?>">
                                        <button type="submit" class="btn <?= $value['status'] === 'aktif' ? 'btn-warning' : 'btn-success' ?> btn-sm">
                                            <?= $value['status'] === 'aktif' ? 'Nonaktifkan' : 'Aktifkan' ?>
                                        </button>
                                    </form>
                                    <form action="<?= site_url('deleteMesin/' . esc($value['id_mesin'])); ?>" method="POST" style="display:inline;">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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

<!-- Modal Tambah Bahan -->
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

<script>
    $(document).ready(function() {
        // Fungsi untuk membuka modal tambah bahan
        window.modalTambahBahan = function(idMesin) {
            // Reset form sebelum menampilkan modal
            $('#bahanContainer').html('');
            $('#modal_id_mesin').val(idMesin);

            // Ambil data bahan yang tersedia via AJAX
            $.ajax({
                url: '<?= site_url('DataManagement/DataMesin/getBahanOptions') ?>',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        addBahanRow(response.data);
                        $('#modalTambahBahan').modal('show');

                        // Setelah modal terbuka, ambil dan tampilkan data bahan yang sudah ada
                        loadExistingBahan(idMesin);
                    }
                },
                error: function() {
                    alert('Gagal mengambil data bahan');
                }
            });
        }

        // Fungsi untuk memuat data bahan yang sudah ada
        function loadExistingBahan(idMesin) {
            $.ajax({
                url: '<?= site_url('DataManagement/DataMesin/getExistingBahan') ?>/' + idMesin,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Update tampilan di tabel
                        let bahanText = response.data.map(item =>
                            `${item.nama_bahan} (${item.jumlah_bahan}ml)`
                        ).join(' | ');

                        if (bahanText === '') {
                            bahanText = '-';
                        }

                        $(`#bahan-list-${idMesin}`).text(bahanText);
                    }
                }
            });
        }

        // Fungsi untuk menambahkan baris bahan
        function addBahanRow(bahanOptions) {
            let optionsHtml = '<option value="" disabled selected>Pilih Bahan</option>';

            if (Array.isArray(bahanOptions)) {
                bahanOptions.forEach(item => {
                    if (item && item.id_bahan && item.nama_bahan) {
                        optionsHtml += `<option value="${item.id_bahan}">${item.nama_bahan}</option>`;
                    }
                });
            }

            const bahanRow = `
                <div class="form-group d-flex align-items-center mb-2">
                    <select name="id_bahan[]" class="form-control me-2" required>
                        ${optionsHtml}
                    </select>
                    <input type="number" name="jumlah[]" class="form-control me-2" placeholder="Jumlah (ml)" required min="0">
                    <button type="button" class="btn btn-success tambahBahan me-2">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger removeBahan">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;

            $('#bahanContainer').append(bahanRow);
        }

        // Event handler untuk tombol tambah bahan
        $(document).on('click', '.tambahBahan', function() {
            // Ambil baris bahan pertama sebagai template
            const firstRow = $('select[name="id_bahan[]"]').first().closest('.form-group');
            // Clone baris tersebut
            const newRow = firstRow.clone();
            // Reset nilai-nilai di baris baru
            newRow.find('select').val('');
            newRow.find('input[type="number"]').val('');
            // Tambahkan ke container
            $('#bahanContainer').append(newRow);
        });

        // Event handler untuk tombol hapus bahan
        $(document).on('click', '.removeBahan', function() {
            if ($('#bahanContainer .form-group').length > 1) {
                $(this).closest('.form-group').remove();
            } else {
                alert('Minimal harus ada satu bahan!');
            }
        });
    });
</script>

<?= $this->endSection(); ?>