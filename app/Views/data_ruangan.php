<?= $this->extend('layout/default'); ?>

<?= $this->section('content'); ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <h4>Data Ruangan</h4>
            <div class="section-header-button">
                <a href="<?= site_url('tambah_ruangan'); ?>" class="btn btn-success">Tambah Ruangan</a>
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
                            <th>Ruangan</th>
                            <th>Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ruangan as $key => $value): ?>
                            <tr>

                                <td><?= esc($key + 1); ?></td>
                                <td><?= esc($value['nama_ruangan']); ?></td>
                                <td id="barang-list-<?= esc($value['id_ruangan']); ?>">
                                    <?php if (!empty($value['barang']) && is_array($value['barang'])): ?>

                                        <?php
                                        $barangList = [];


                                        foreach ($value['barang'] as $barang) {
                                            if (is_array($barang) && isset($barang['nama_barang']) && isset($barang['jumlah'])) {
                                                $barangList[] = esc($barang['nama_barang']) . ' : (' . esc($barang['jumlah']) . 'pcs)';
                                            }
                                        }
                                        echo implode(' | ', $barangList);
                                        ?>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <button type="button" class="btn btn-primary btn-sm me-2 mr-2" onclick="modalTambahBarang('<?= esc($value['id_ruangan']); ?>')">
                                        Tambah Barang
                                    </button>
                                    <form action="<?= site_url('updateStatus/' . $value['id_ruangan']); ?>" method="POST" style="display:inline;" class="mr-2">
                                        <?= csrf_field(); ?>
                                        <input type="hidden" name="_method" value="PUT">
                                    </form>
                                    <form action="<?= site_url('deleteRuangan/' . esc($value['id_ruangan'])); ?>" method="POST" style="display:inline;">
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



<!-- Modal Tambah Barang Pada Ruangan -->
<div class="modal fade" id="modalTambahBarang" tabindex="-1" role="dialog" aria-labelledby="modalTambahBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahBarangLabel">Tambah Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <form action="<?= site_url('storeRuangan'); ?>" method="post">
                    <?= csrf_field(); ?>
                    <input type="hidden" name="id_ruangan" id="modal_id_ruangan" value="">
                    <div id="barangContainer">
                        <div class="form-group d-flex align-items-center mb-2">
                            <select name="id_barang[]" class="form-control me-2" required>
                                <option value="" disabled selected>Pilih Barang</option>

                            </select>
                            <input type="number" name="jumlah[]" class="form-control me-2" placeholder="Jumlah (Pcs)" required min="0">
                            <button type="button" class="btn btn-success  me-2 tambahBarang">
                                <i class="fas fa-plus"></i>
                            </button>
                            <button type="button" class="btn btn-danger removeBarang">
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
        // Fungsi untuk membuka modal tambah barang
        window.modalTambahBarang = function(idRuangan) {
            // Reset form sebelum menampilkan modal
            $('#barangContainer').html('');
            $('#modal_id_ruangan').val(idRuangan);

            // Ambil data barang yang tersedia via AJAX
            $.ajax({
                url: '<?= site_url('DataManagement/DataRuangan/getBarangOptions') ?>',
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        addbarangRow(response.data);
                        $('#modalTambahBarang').modal('show');

                        // Setelah modal terbuka, ambil dan tampilkan data barang yang sudah ada
                        loadExistingbarang(idRuangan);
                    }
                },
                error: function() {
                    alert('Gagal mengambil data barang');
                }
            });
        }

        // Fungsi untuk memuat data barang yang sudah ada
        function loadExistingbarang(idRuangan) {
            $.ajax({
                url: '<?= site_url('DataManagement/DataRuangan/getExistingBarang') ?>/' + idRuangan,
                type: 'GET',
                success: function(response) {
                    if (response.success) {
                        // Update tampilan di tabel
                        let barangText = response.data.map(item =>
                            `${item.nama_barang} (${item.jumlah} pcs)`
                        ).join(' | ');

                        if (barangText === '') {
                            barangText = '-';
                        }

                        $(`#barang-list-${idRuangan}`).text(barangText);
                    }
                }
            });
        }

        // Fungsi untuk menambahkan baris barang
        function addbarangRow(barangOptions) {
            let optionsHtml = '<option value="" disabled selected>Pilih Barang</option>';

            if (Array.isArray(barangOptions)) {
                barangOptions.forEach(item => {
                    if (item && item.id_barang && item.nama_barang) {
                        optionsHtml += `<option value="${item.id_barang}">${item.nama_barang}</option>`;
                    }
                });
            }

            const barangRow = `
                <div class="form-group d-flex align-items-center mb-2">
                    <select name="id_barang[]" class="form-control me-2" required>
                        ${optionsHtml}
                    </select>
                    <input type="number" name="jumlah[]" class="form-control me-2" placeholder="Jumlah (Pcs)" required min="0">
                    <button type="button" class="btn btn-success tambahBarang me-2">
                        <i class="fas fa-plus"></i>
                    </button>
                    <button type="button" class="btn btn-danger removebarang">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            `;

            $('#barangContainer').append(barangRow);
        }

        // Event handler untuk tombol tambah barang
        $(document).on('click', '.tambahBarang', function() {
            // Ambil baris barang pertama sebagai template
            const firstRow = $('select[name="id_barang[]"]').first().closest('.form-group');

            // Clone baris tersebut
            const newRow = firstRow.clone();

            // Reset nilai-nilai di baris baru
            newRow.find('select').val('');
            newRow.find('input[type="number"]').val('');

            // Tambahkan ke container
            $('#barangContainer').append(newRow);

            // Pastikan tombol tambah di baris baru memiliki event handler
            console.log('Baris baru ditambahkan!');
        });
        // Event handler untuk tombol hapus barang
        $(document).on('click', '.removebarang', function() {
            if ($('#barangContainer .form-group').length > 1) {
                $(this).closest('.form-group').remove();
            } else {
                alert('Minimal harus ada satu barang!');
            }
        });
    });
</script>

<?= $this->endSection(); ?>