<?= $this->extend('layout/default') ?>

<?= $this->section('content') ?>
<section class="section">
    <div class="card">
        <div class="card-header">
            <div class="section-header-button">
                <a href="<?= site_url('distribusi'); ?>" class="btn"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h4>Timbangan Bersih</h4>
        </div>
        <div class="card-body">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success  alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('message'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger  alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error'); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Invoice</th>
                        <th>Nama Ruangan</th>
                        <th>Berat Kotor</th>
                        <th>Berat Bersih</th>
                        <th>tanggal_pengiriman</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($timbangan_bersih as $key => $t): ?>
                        <tr>
                            <td><?= esc($key + 1) ?></td>
                            <td><?= esc($t['no_invoice']) ?></td>
                            <td><?= esc($t['nama_ruangan']) ?></td>
                            <td><?= esc($t['berat_kotor']) ?> kg</td>
                            <td><?= esc($t['berat_bersih'] ?? '-') ?> kg</td>
                            <td><?= esc($t['tanggal_pengiriman'] ?? '-') ?> </td>
                            <td>
                                <span class="badge 
                                    <?= $t['status'] === 'pending' ? 'badge-danger' : ($t['status'] === 'process' ? 'badge-primary' : ($t['status'] === 'delivered' ? 'badge-success' : 'badge-secondary')) ?>">
                                    <?= ucfirst(esc($t['status'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($t['status'] == 'process' || $t['status'] == 'delivered'): ?>
                                    <button type="button" class="btn btn-info btn-sm"
                                        onclick="showDetail(<?= $t['id_timbangan_bersih'] ?>)">
                                        Detail & Barang
                                    </button>
                                <?php endif ?>

                                <?php if ($t['status'] == 'pending' && !empty($t['berat_bersih'])): ?>
                                    <a href="<?= site_url('distribusi/timbangan_bersih/statMove/' . $t['id_timbangan_bersih']); ?>"
                                        class="btn btn-primary btn-sm">
                                        Pengiriman
                                    </a>
                                <?php elseif ($t['status'] == 'pending'): ?>
                                    <button type="button" class="btn btn-warning btn-sm" onclick="showDetail(<?= $t['id_timbangan_bersih'] ?>)">
                                        Isi Data
                                    </button>
                                <?php elseif ($t['status'] == 'process'): ?>
                                    <a href="<?= site_url('distribusi/pengiriman/konfirmasi/' . $t['id_timbangan_bersih']); ?>" class="btn btn-success btn-sm">
                                        Terkirim
                                    </a>
                            </td>
                        </tr>
                    <?php endif ?>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal Detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="header-content-timbangan-bersih">
                    <p id="ruanganName">Nama Ruangan: <strong><?= esc($t['nama_ruangan']) ?></strong></p>
                </div>
                <div class="detail-content">
                    <div class="detail-table">
                        <h4 class='header-judul'>Timbangan Kotor</h4>
                        <table class="table">
                            <tbody id="detailTableBodyKotor">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </tbody>
                        </table>
                    </div>
                    <div class="detail-table">
                        <h4 class='header-judul'>Timbangan Bersih</h4>
                        <table class="table">
                            <tbody id="detailTableBodyBersih">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                            </tbody>
                        </table>
                    </div>
                </div>
                <form id="beratBersihForm" . $t['id'])>

                    <!-- Tambahkan hidden input untuk CSRF -->
                    <input type="hidden" name="<?= csrf_token() ?>" value="<?= csrf_hash() ?>" class="csrf-token">

                    <div class="form-group" id="beratBersihInputContainer">
                        <label for="berat_bersih">Berat Bersih (kg)</label>
                        <input type="number" class="form-control" value="" id="berat_bersih" name="berat_bersih" step="1" require-min=1>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="saveButton" onclick="saveBerat()">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    let currentTimbanganId = null;

    function showDetail(id) {
        currentTimbanganId = id;

        fetch(`<?= base_url('distribusi/timbangan_bersih/detail') ?>/${id}`)
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }

                const tbodyKotor = document.getElementById('detailTableBodyKotor');
                const tbodyBersih = document.getElementById('detailTableBodyBersih');
                // Mengisi tabel
                tbodyKotor.innerHTML = '';
                tbodyBersih.innerHTML = '';
                response.data.detailBarangKotor.forEach(item => {
                    tbodyKotor.innerHTML += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.jumlah}</td>
                </tr>
            `;
                });
                response.data.detailBarang.forEach(item => {
                    tbodyBersih.innerHTML += `
                <tr>
                    <td>${item.nama_barang}</td>
                    <td>${item.jumlah_barang}</td>
                </tr>
            `;
                });


                document.getElementById('berat_bersih').value = response.data.timbananBersih.berat_bersih || '';
                const status = response.data.timbananBersih.status;

                if (status === 'process' || status === 'delivered') {
                    document.getElementById('beratBersihInputContainer').style.display = 'none';
                    document.getElementById('saveButton').style.display = 'none';
                } else if (status === 'pending') {
                    document.getElementById('beratBersihInputContainer').style.display = 'block';
                    document.getElementById('saveButton').style.display = 'block';
                }

                $('#detailModal').modal('show');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengambil data: ' + error.message);
            });
    }


    function saveBerat() {
        const beratBersih = document.getElementById('berat_bersih').value;
        const csrfToken = document.querySelector('.csrf-token').value;
        const csrfName = document.querySelector('.csrf-token').name;

        if (!beratBersih) {
            alert("Berat bersih tidak boleh kosong!");
            return;
        }



        const formData = new FormData();
        formData.append('berat_bersih', beratBersih);
        formData.append(csrfName, csrfToken);

        fetch(`<?= base_url('distribusi/timbangan_bersih/postDetail') ?>/${currentTimbanganId}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => response.json())
            .then(response => {
                if (!response.success) {
                    throw new Error(response.message);
                }

                $('#detailModal').modal('hide');
                setTimeout(() => {
                    location.reload();
                }, 500);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menyimpan data: ' + error.message);
            });
    }

    function validateFormModal() {
        // Ambil elemen input dari modal
        var beratBersih = document.getElementById('berat_bersih').value; // Pastikan input ini ada di modal

        console.log("Berat Bersih:", beratBersih);
        // Cek apakah ruangan dan berat bersih sudah diisi
        if (ruangan === "" || beratBersih === "") {
            alert("Pilih ruangan tujuan dan masukan berat bersih");
            return false; // Mencegah tombol melakukan submit jika input ini kosong
        }

        // Jika input sudah terisi, return true untuk melanjutkan aksi tombol
        return true;
    }
</script>
<?= $this->endSection() ?>